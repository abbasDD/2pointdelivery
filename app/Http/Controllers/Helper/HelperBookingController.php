<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\FcmController;
use App\Http\Controllers\GetEstimateController;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\Helper;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\ServiceCategory;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserWallet;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\Laravel\Facades\Image;

class HelperBookingController extends Controller
{
    protected $getBookingController;
    protected $getEstimateController;
    private $fcm;

    public function __construct(BookingController $getBookingController, GetEstimateController $getEstimateController, FcmController $fcm)
    {
        $this->getBookingController = $getBookingController;
        $this->getEstimateController = $getEstimateController;
        $this->fcm = $fcm;
    }

    public function index()
    {
        $userId = Auth::user()->id;

        $bookings = Booking::with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->orderBy('bookings.updated_at', 'desc')->get();

        // dd($bookings);

        foreach ($bookings as $booking) {
            if ($booking->helper_user_id != NULL) {
                $booking->helper = Helper::where('user_id', $booking->helper_user_id)->first();
            }

            if ($booking->helper_user_id2 != NULL) {
                $booking->helper = Helper::where('user_id', $booking->helper_user_id2)->first();
            }

            $booking->client = Client::where('user_id', $booking->client_user_id)->first();

            $booking->payment = null;

            if ($booking->booking_type == 'delivery') {
                $booking->payment = BookingDelivery::where('booking_id', $booking->id)->first();
            }

            if ($booking->booking_type == 'moving') {
                $booking->payment = BookingMoving::where('booking_id', $booking->id)->first();
            }
        }

        return view('helper.bookings.index', compact('bookings'));
    }

    public function acceptBooking(Request $request)
    {

        // checkHelperRequirements
        $this->getBookingController->checkHelperRequirements();

        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->client_user_id == Auth::user()->id) {
            return redirect()->back()->with('error', 'You can not accept your own booking');
        }

        // Check if booking status is pending
        if ($booking->status == 'pending') {

            switch ($booking->booking_type) {
                case 'delivery':
                    // Get booking delivery data
                    $bookingPayment = BookingDelivery::where('booking_id', $booking->id)->first();
                    if (!$bookingPayment) {
                        return redirect()->back()->with('error', 'Booking payment not found');
                    }

                    $booking->status = 'accepted';
                    $booking->helper_user_id = Auth::user()->id;
                    $booking->save();
                    break;
                case 'moving':
                    $bookingPayment = BookingMoving::where('booking_id', $booking->id)->first();
                    if (!$bookingPayment) {
                        return redirect()->back()->with('error', 'Booking payment not found');
                    }

                    // Check if helper_user_id is null
                    if ($booking->helper_user_id == null) {
                        // $booking->status = 'accepted';
                        $booking->helper_user_id = Auth::user()->id;
                        $booking->save();
                    } else {
                        // Check if same user_id is in helper_user_id
                        if ($booking->helper_user_id == Auth::user()->id) {
                            return redirect()->back()->with('error', 'You have already accepted this booking');
                        }
                        $booking->status = 'accepted';
                        $booking->helper_user_id2 = Auth::user()->id;
                        $booking->save();
                    }
                    break;
                case 'secureship':
                    return redirect()->back()->with('error', 'Unsupported booking type');
                    break;
                default:
                    return redirect()->back()->with('error', 'Booking type not found');
                    break;
            }


            $bookingPayment->accepted_at = Carbon::now();
            $bookingPayment->save();

            // Send email
            $emailTemplateController = app(EmailTemplateController::class);
            $emailTemplateController->bookingStatusEmail($booking);

            // Send Notification
            UserNotification::create([
                'sender_user_id' => Auth::user()->id,
                'receiver_user_id' => $booking->client_user_id,
                'receiver_user_type' => 'client',
                'reference_id' => $booking->id,
                'type' => 'booking',
                'title' => 'Booking Accepted',
                'content' => 'Your booking has been accepted.',
                'read' => 0
            ]);

            // Send Push Notification to client
            $this->fcm->sendPushNotificationToUser($booking->client_user_id, 'Booking Accepted', 'Your booking has been accepted.', 'booking', $booking->id, 'booking', $booking->id);

            return redirect()->route('helper.booking.show', ['id' => $booking->id])->with('success', 'Booking accepted successfully!');
        }


        return redirect()->back()->with('error', 'Booking already accepted');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $userId = Auth::user()->id;

        $booking = Booking::where('id', $request->id)
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Check if booking_type is secureship
        if ($booking->booking_type == 'secureship') {
            return redirect()->back()->with('error', 'Unsupported booking type');
        }

        // Check if helper_user_id && helper_user_id is equal to Auth::user()->id
        if ($booking->helper_user_id != Auth::user()->id && $booking->helper_user_id2 != Auth::user()->id) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Conveert booking status to current
        $booking->currentStatus = $this->getBookingController->getBookingCurrentStatus($booking->status);

        $booking->moverCount = 0;

        if ($booking->helper_user_id !== null) {
            $booking->moverCount++;
        }

        if ($booking->helper_user_id2 !== null) {
            $booking->moverCount++;
        }

        // dd($booking->currentStatus);

        // Client view false
        $clientView = false;

        // Helper view true
        $helperView = true;

        // Getting booking payment data
        if ($booking->booking_type == 'delivery') {
            // Getting booking payment data
            $bookingPayment = BookingDelivery::where('booking_id', $booking->id)->first();
        }

        if ($booking->booking_type == 'moving') {
            $bookingPayment = BookingMoving::where('booking_id', $booking->id)->first();
        }

        // Get helper Data
        $helperData = null;
        if ($booking->helper_user_id) {
            $helperData = Helper::where('user_id', $booking->helper_user_id)->first();
        }

        $helperData2 = null;
        if ($booking->helper_user_id2) {
            $helperData2 = Helper::where('user_id', $booking->helper_user_id2)->first();
        }

        // Get client data
        $clientData = null;
        if ($booking->client_user_id) {
            $clientData = Client::where('user_id', $booking->client_user_id)->first();
        }

        // Get vehicle data
        $vehicleTypeData = null;
        if ($booking->service_category_id) {
            $serviceCategoryData = ServiceCategory::where('id', $booking->service_category_id)->first();
            if ($serviceCategoryData) {
                $vehicleTypeData = VehicleType::where('id', $serviceCategoryData->vehicle_type_id)->first();
            }
        }


        // Get helper vehicle data
        $helperVehicleData = null;
        if ($booking->helper_user_id) {
            $helperVehicleData = HelperVehicle::where('user_id', $booking->helper_user_id)->first();
        }
        // dd($helperVehicleData);

        // Get helper2 vehicle data
        $helper2VehicleData = null;
        if ($booking->helper_user_id2) {
            $helper2VehicleData = HelperVehicle::where('user_id', $booking->helper_user_id2)->first();
        }

        // // Check if invoice already created
        // if ($booking->invoice_file == null) {
        //     // Generate invoice from this url bookingInvoicePDF
        //     // $this->generateInvoice($booking->id);
        //     $booking_invoice = $this->getEstimateController->generateInvoice($booking->id);
        //     if ($booking_invoice) {
        //         // Update booking
        //         Booking::where('id', $booking->id)->update([
        //             'invoice_file' => $booking_invoice
        //         ]);t
        //     }
        // }

        // // Check if label_file already created
        // if ($booking->label_file == null) {
        //     // Generate label from this url bookingLabelPDF
        //     // $this->generateLabel($booking->id);
        //     $booking_label = $this->getEstimateController->generateLabel($booking->id);
        //     if ($booking_label) {
        //         // Update booking
        //         Booking::where('id', $booking->id)->update([
        //             'label_file' => $booking_label
        //         ]);
        //     }
        // }

        // dd($booking);

        return view('helper.bookings.show', compact('booking', 'bookingPayment', 'helperData', 'helperData2', 'clientData', 'vehicleTypeData', 'helper2VehicleData', 'helperVehicleData', 'clientView', 'helperView'));
    }



    // Start Booking
    public function start(Request $request)
    {

        $userId = Auth::user()->id;

        $booking = Booking::where('id', $request->id)
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->booking_type == 'secureship') {
            return redirect()->back()->with('error', 'Unable to start secure ship booking');
        }

        if ($booking->status == 'accepted') {


            // Check if booking->booking_type is delivery
            if ($booking->booking_type == 'delivery') {
                // Get booking delivery data
                $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
                if (!$bookingDelivery) {
                    return redirect()->back()->with('error', 'Booking delivery not found');
                }
            }

            // Check if booking->booking_type is moving
            if ($booking->booking_type == 'moving') {
                // Get booking moving data
                $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
                if (!$bookingMoving) {
                    return redirect()->back()->with('error', 'Booking moving not found');
                }
            }


            // if start_booking_image is not set then back with error
            if (!$request->hasFile('start_booking_image')) {
                return redirect()->back()->with('error', 'Please select start booking image');
            }

            // if start_booking_image is not set then back with error
            if (!$request->hasFile('signatureStart')) {
                return redirect()->back()->with('error', 'Please select start booking image');
            }

            $start_booking_image = null;

            $signatureStart = null;

            // Upload booking image
            if ($request->hasFile('start_booking_image')) {
                $image = Image::read($request->file('start_booking_image'));

                // Main Image Upload on Folder Code
                $imageName = time() . rand(0, 999) . '.' . $request->file('start_booking_image')->getClientOriginalExtension();
                $destinationPath = public_path('images/bookings/');
                $image->save($destinationPath . $imageName);

                $start_booking_image = $imageName;
            }

            // Upload signature start image
            if ($request->hasFile('signatureStart')) {
                $image = Image::read($request->file('signatureStart'));

                // Main Image Upload on Folder Code
                $imageName = time() . rand(0, 999) . '.' . $request->file('signatureStart')->getClientOriginalExtension();
                $destinationPath = public_path('images/bookings/');
                $image->save($destinationPath . $imageName);

                $signatureStart = $imageName;
            }


            // Update Booking for delivery
            if ($booking->booking_type == 'delivery') {
                $bookingDelivery->signatureStart = $signatureStart;
                $bookingDelivery->start_booking_image = $start_booking_image;
                $bookingDelivery->start_booking_at = Carbon::now();
                $bookingDelivery->save();
            }

            // Update Booking for moving
            if ($booking->booking_type == 'moving') {
                $bookingMoving->signatureStart = $signatureStart;
                $bookingMoving->start_booking_image = $start_booking_image;
                $bookingMoving->start_booking_at = Carbon::now();
                $bookingMoving->save();
            }


            $booking->status = 'started';
            $booking->save();

            // Send Notification
            $userNotification = UserNotification::create([
                'sender_user_id' => Auth::user()->id,
                'receiver_user_id' => $booking->client_user_id,
                'receiver_user_type' => 'client',
                'reference_id' => $booking->id,
                'type' => 'booking',
                'title' => 'Booking Started',
                'content' => 'Your booking has been started.',
                'read' => 0
            ]);

            // dd($booking);

            return redirect()->back()->with('success', 'Booking started successfully!');
        }

        return redirect()->back()->with('error', 'Booking not accepted');

        // return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData'));
    }

    // Cancel Booking
    public function cancel(Request $request)
    {
        $userId = Auth::user()->id;

        $booking = Booking::where('id', $request->id)
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->booking_type == 'secureship') {
            return redirect()->back()->with('error', 'Unable to cancel secure ship booking');
        }

        if ($booking->status == 'accepted') {

            // Check if booking->booking_type is delivery
            if ($booking->booking_type == 'delivery') {
                // Get booking delivery data
                $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
                if (!$bookingDelivery) {
                    return redirect()->back()->with('error', 'Booking delivery not found');
                }

                // Update booking delivery
                $bookingDelivery->save();
            }


            // Check if booking->booking_type is moving
            if ($booking->booking_type == 'moving') {
                // Get booking moving data
                $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
                if (!$bookingMoving) {
                    return redirect()->back()->with('error', 'Booking moving not found');
                }

                // Update booking moving
                $bookingMoving->save();
            }


            // Update Booking
            $booking->status = 'cancelled';
            $booking->save();

            // Send Notification
            UserNotification::create([
                'sender_user_id' => Auth::user()->id,
                'receiver_user_id' => $booking->client_user_id,
                'receiver_user_type' => 'client',
                'reference_id' => $booking->id,
                'type' => 'booking',
                'title' => 'Booking is cancelled',
                'content' => 'Your booking is cancelled by the helper.',
                'read' => 0
            ]);

            return redirect()->back()->with('success', 'Booking cancelled successfully!');
        }

        return redirect()->back()->with('error', 'Booking not accepted');
    }

    // inTransit Booking
    public function inTransit(Request $request)
    {
        $userId = Auth::user()->id;

        $booking = Booking::where('id', $request->id)
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->booking_type == 'secureship') {
            return redirect()->back()->with('error', 'Unable to start secure ship booking');
        }

        if ($booking->status == 'started') {

            // Check if booking->booking_type is delivery
            if ($booking->booking_type == 'delivery') {
                // Get booking delivery data
                $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
                if (!$bookingDelivery) {
                    return redirect()->back()->with('error', 'Booking delivery not found');
                }

                // Update booking delivery
                $bookingDelivery->start_intransit_at = Carbon::now();
                $bookingDelivery->save();
            }


            // Check if booking->booking_type is moving
            if ($booking->booking_type == 'moving') {
                // Get booking moving data
                $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
                if (!$bookingMoving) {
                    return redirect()->back()->with('error', 'Booking moving not found');
                }

                // Update booking moving
                $bookingMoving->start_intransit_at = Carbon::now();
                $bookingMoving->save();
            }


            // Update Booking
            $booking->status = 'in_transit';
            $booking->save();

            // Send Notification
            UserNotification::create([
                'sender_user_id' => Auth::user()->id,
                'receiver_user_id' => $booking->client_user_id,
                'receiver_user_type' => 'client',
                'reference_id' => $booking->id,
                'type' => 'booking',
                'title' => 'Booking In Transit',
                'content' => 'Your booking is in transit.',
                'read' => 0
            ]);

            return redirect()->back()->with('success', 'Booking in transit successfully!');
        }

        return redirect()->back()->with('error', 'Booking not started');
    }

    // Start Booking
    public function complete(Request $request)
    {

        $userId = Auth::user()->id;

        $booking = Booking::where('id', $request->id)
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'in_transit') {
            return redirect()->back()->with('error', 'Booking is not in transit');
        }

        // Check if booking->booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            // Get booking delivery data
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
            if (!$bookingDelivery) {
                return redirect()->back()->with('error', 'Booking delivery not found');
            }
        }


        // Check if booking->booking_type is moving
        if ($booking->booking_type == 'moving') {
            // Get booking moving data
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
            if (!$bookingMoving) {
                return redirect()->back()->with('error', 'Booking moving not found');
            }
        }


        // if complete_booking_image is not set then back with error
        if (!$request->hasFile('complete_booking_image')) {
            return redirect()->back()->with('error', 'Please select complete booking image');
        }

        // if start_booking_image is not set then back with error
        if (!$request->hasFile('signatureCompleted')) {
            return redirect()->back()->with('error', 'Please select start booking image');
        }

        $complete_booking_image = null;

        $signatureCompleted = null;

        // Upload booking image
        if ($request->hasFile('complete_booking_image')) {
            $image = Image::read($request->file('complete_booking_image'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('complete_booking_image')->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $image->save($destinationPath . $imageName);

            $complete_booking_image = $imageName;
        }

        // Upload completed signature image
        if ($request->hasFile('signatureCompleted')) {
            $image = Image::read($request->file('signatureCompleted'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('signatureCompleted')->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $image->save($destinationPath . $imageName);

            $signatureCompleted = $imageName;
        }


        // Update Booking for delivery
        if ($booking->booking_type == 'delivery') {
            $bookingDelivery->signatureCompleted = $signatureCompleted;
            $bookingDelivery->complete_booking_image = $complete_booking_image;
            $bookingDelivery->complete_booking_at = Carbon::now();
            $bookingDelivery->save();
        }


        // Update Booking for moving
        if ($booking->booking_type == 'moving') {
            $bookingMoving->signatureCompleted = $signatureCompleted;
            $bookingMoving->complete_booking_image = $complete_booking_image;
            $bookingMoving->complete_booking_at = Carbon::now();
            $bookingMoving->save();
        }


        $booking->status = 'completed';
        $booking->save();

        // Add to UserWallet for Delivery
        if ($booking->booking_type == 'delivery') {
            // Add to wallet of helper as Delivery has one helper only
            UserWallet::create([
                'user_id' => $booking->helper_user_id,
                'user_type' => 'helper',
                'type' => 'earned',
                'amount' => $bookingDelivery->helper_fee,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'wallet',
                'transaction_id' => '',
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);
        }

        // Add to UserWallet for Moving
        if ($booking->booking_type == 'moving') {
            // Add to wallet of 2 helpers as Moving has 2 helpers
            $one_helper_fee = $bookingMoving->helper_fee;

            // Add to helper_1 wallet
            UserWallet::create([
                'user_id' => $booking->helper_user_id,
                'user_type' => 'helper',
                'type' => 'earned',
                'amount' => $one_helper_fee,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'wallet',
                'transaction_id' => '',
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);
            // Add to helper_2 wallet
            UserWallet::create([
                'user_id' => $booking->helper_user_id2,
                'user_type' => 'helper',
                'type' => 'earned',
                'amount' => $one_helper_fee,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'wallet',
                'transaction_id' => '',
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);
        }

        // Send Notification
        UserNotification::create([
            'sender_user_id' => Auth::user()->id,
            'receiver_user_id' => $booking->client_user_id,
            'receiver_user_type' => 'client',
            'reference_id' => $booking->id,
            'type' => 'booking',
            'title' => 'Booking Completed',
            'content' => 'Your booking is completed.',
            'read' => 0
        ]);

        // Send email
        $emailTemplateController = app(EmailTemplateController::class);
        $emailTemplateController->bookingStatusEmail($booking);

        return redirect()->back()->with('success', 'Booking completed successfully!');

        // return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData'));
    }

    // incomplete Booking
    public function incomplete(Request $request)
    {

        if (!$request->incomplete_reason) {
            return redirect()->back()->with('error', 'Please provide incomplete reason');
        }

        $userId = Auth::user()->id;

        $booking = Booking::where('id', $request->id)
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status == 'completed') {
            return redirect()->back()->with('error', 'Booking already completed');
        }



        // Check if booking->booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            // Get booking delivery data
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
            if (!$bookingDelivery) {
                return redirect()->back()->with('error', 'Booking delivery not found');
            }
        }

        // Check if booking->booking_type is moving
        if ($booking->booking_type == 'moving') {
            // Get booking moving data
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
            if (!$bookingMoving) {
                return redirect()->back()->with('error', 'Booking moving not found');
            }
        }


        // Update Booking
        $booking->status = 'incomplete';
        $booking->save();


        // Check if booking->booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            // Update booking delivery
            $bookingDelivery->incomplete_reason = $request->incomplete_reason;
            $bookingDelivery->incomplete_booking_at = Carbon::now();
            $bookingDelivery->save();
        }


        // Check if booking->booking_type is moving
        if ($booking->booking_type == 'moving') {
            // Update booking moving
            $bookingMoving->incomplete_reason = $request->incomplete_reason;
            $bookingMoving->incomplete_booking_at = Carbon::now();
            $bookingMoving->save();
        }

        // Add to UserWallet for Delivery
        if ($booking->booking_type == 'delivery') {
            // Add to wallet of helper as Delivery has one helper only
            UserWallet::create([
                'user_id' => $booking->helper_user_id,
                'user_type' => 'helper',
                'type' => 'earned',
                'amount' => $bookingDelivery->helper_fee,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'wallet',
                'transaction_id' => '',
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);
        }

        // Add to UserWallet for Moving
        if ($booking->booking_type == 'moving') {
            // Add to wallet of 2 helpers as Moving has 2 helpers
            $one_helper_fee = $bookingMoving->helper_fee;

            // Add to helper_1 wallet
            UserWallet::create([
                'user_id' => $booking->helper_user_id,
                'user_type' => 'helper',
                'type' => 'earned',
                'amount' => $one_helper_fee,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'wallet',
                'transaction_id' => '',
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);
            // Add to helper_2 wallet
            UserWallet::create([
                'user_id' => $booking->helper_user_id2,
                'user_type' => 'helper',
                'type' => 'earned',
                'amount' => $one_helper_fee,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'wallet',
                'transaction_id' => '',
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);
        }

        // Send Notification
        UserNotification::create([
            'sender_user_id' => Auth::user()->id,
            'receiver_user_id' => $booking->client_user_id,
            'receiver_user_type' => 'client',
            'reference_id' => $booking->id,
            'type' => 'booking',
            'title' => 'Booking Incomplete',
            'content' => 'Your booking is incomplete.',
            'read' => 0
        ]);

        // Send email
        $emailTemplateController = app(EmailTemplateController::class);
        $emailTemplateController->bookingStatusEmail($booking);


        return redirect()->back()->with('success', 'Booking maked as incomplete!');
    }
}
