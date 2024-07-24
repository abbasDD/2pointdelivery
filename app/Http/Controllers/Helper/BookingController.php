<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetEstimateController;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\Helper;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\ServiceCategory;
use App\Models\UserNotification;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    protected $getEstimateController;

    public function __construct(GetEstimateController $getEstimateController)
    {
        $this->middleware('auth');

        $this->getEstimateController = $getEstimateController;
    }

    public function index()
    {

        $bookings = Booking::with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
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

        // Check if helper completed its profile
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        if (!$helper) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if personal detail completed
        if ($helper->first_name == null || $helper->last_name == null) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if address detail completed
        if ($helper->city == null || $helper->state == null || $helper->country == null) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if vehicle detail completed
        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if (!$helperVehicle) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if helper is_approved is 0
        if ($helper->is_approved == 0) {
            return redirect()->back()->with('error', 'In order to accept booking, waiting for admin approval');
        }

        // Check if profile is company profile
        if ($helper->company_enabled == 1) {
            // Check if company detail completed
            $companyData = HelperCompany::where('user_id', auth()->user()->id)->first();

            if (!$companyData) {
                return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
            }

            // Check if company detail completed

            if ($companyData->company_alias == null || $companyData->city == null) {
                return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
            }
        }

        // Check if client approve helper
        if ($helper->is_approved == 0) {
            return redirect()->route('helper.index')->with('error', 'Admin have not accept your request yet');
        }


        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->client_user_id == auth()->user()->id) {
            return redirect()->back()->with('error', 'You can not accept your own booking');
        }


        if ($booking->status != 'pending') {
            return redirect()->back()->with('error', 'Booking already accepted');
        }

        if ($booking->booking_type == 'moving') {
            $bookingPayment = BookingMoving::where('booking_id', $booking->id)->first();

            // Check if helper_user_id is null
            if ($booking->helper_user_id == null) {
                // $booking->status = 'accepted';
                $booking->helper_user_id = auth()->user()->id;
                $booking->save();
            } else {
                // Check if same user_id is in helper_user_id
                if ($booking->helper_user_id == auth()->user()->id) {
                    return redirect()->back()->with('error', 'You have already accepted this booking');
                }
                $booking->status = 'accepted';
                $booking->helper_user_id2 = auth()->user()->id;
                $booking->save();
            }
        } else {
            $bookingPayment = BookingDelivery::where('booking_id', $booking->id)->first();

            $booking->status = 'accepted';
            $booking->helper_user_id = auth()->user()->id;
            $booking->save();
        }

        $bookingPayment->accepted_at = Carbon::now();
        $bookingPayment->save();

        return redirect()->back()->with('success', 'Booking accepted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        $booking->currentStatus = 1;
        // switch to manage booking status
        switch ($booking->status) {
            case 'pending':
                $booking->currentStatus = 0;
                break;
            case 'accepted':
                $booking->currentStatus = 1;
                break;
            case 'started':
                $booking->currentStatus = 2;
                break;
            case 'in_transit':
                $booking->currentStatus = 3;
                break;
            case 'completed':
                $booking->currentStatus = 4;
                break;
            case 'incomplete':
                $booking->currentStatus = 5;
                break;
            default:
                $booking->currentStatus = 1;
                break;
        }

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

        $helper2Data = null;
        if ($booking->helper_user_id2) {
            $helper2Data = Helper::where('user_id', $booking->helper_user_id2)->first();
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

        // Check if invoice already created
        if ($booking->invoice_file == null) {
            // Generate invoice from this url bookingInvoicePDF
            // $this->generateInvoice($booking->id);
            $booking_invoice = $this->getEstimateController->generateInvoice($booking->id);
            if ($booking_invoice) {
                // Update booking
                Booking::where('id', $booking->id)->update([
                    'invoice_file' => $booking_invoice
                ]);
            }
        }

        // Check if label_file already created
        if ($booking->label_file == null) {
            // Generate label from this url bookingLabelPDF
            // $this->generateLabel($booking->id);
            $booking_label = $this->getEstimateController->generateLabel($booking->id);
            if ($booking_label) {
                // Update booking
                Booking::where('id', $booking->id)->update([
                    'label_file' => $booking_label
                ]);
            }
        }

        // dd($booking);

        return view('frontend.bookings.show', compact('booking', 'bookingPayment', 'helperData', 'helper2Data', 'clientData', 'vehicleTypeData', 'helper2VehicleData', 'helperVehicleData', 'clientView', 'helperView'));
    }
    // Start Booking
    public function start(Request $request)
    {

        // return redirect()->back()->with('error', 'Booking not found');

        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'accepted') {
            return redirect()->back()->with('error', 'Booking not accepted');
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
            $file = $request->file('start_booking_image');
            $updatedBookingFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedBookingFilename);

            // Set the profile image attribute to the new file name
            $start_booking_image = $updatedBookingFilename;
        }

        // Upload signature start image
        if ($request->hasFile('signatureStart')) {
            $file = $request->file('signatureStart');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $signatureStart = $updatedFilename;
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
            'sender_user_id' => auth()->user()->id,
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

        // return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData'));
    }

    // inTransit Booking
    public function inTransit(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'started') {
            return redirect()->back()->with('error', 'Booking not started');
        }

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
        $userNotification = UserNotification::create([
            'sender_user_id' => auth()->user()->id,
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

    // Start Booking
    public function complete(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
            ->first();

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
            $file = $request->file('complete_booking_image');
            $updatedBookingFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedBookingFilename);

            // Set the profile image attribute to the new file name
            $complete_booking_image = $updatedBookingFilename;
        }

        // Upload completed signature image
        if ($request->hasFile('signatureCompleted')) {
            $file = $request->file('signatureCompleted');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $signatureCompleted = $updatedFilename;
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

        // Send Notification
        $userNotification = UserNotification::create([
            'sender_user_id' => auth()->user()->id,
            'receiver_user_id' => $booking->client_user_id,
            'receiver_user_type' => 'client',
            'reference_id' => $booking->id,
            'type' => 'booking',
            'title' => 'Booking Completed',
            'content' => 'Your booking is completed.',
            'read' => 0
        ]);

        return redirect()->back()->with('success', 'Booking completed successfully!');

        // return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData'));
    }

    // incomplete Booking
    public function incomplete(Request $request)
    {

        if (!$request->incomplete_reason) {
            return redirect()->back()->with('error', 'Please provide incomplete reason');
        }

        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
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

        // Send Notification
        $userNotification = UserNotification::create([
            'sender_user_id' => auth()->user()->id,
            'receiver_user_id' => $booking->client_user_id,
            'receiver_user_type' => 'client',
            'reference_id' => $booking->id,
            'type' => 'booking',
            'title' => 'Booking Incomplete',
            'content' => 'Your booking is incomplete.',
            'read' => 0
        ]);


        return redirect()->back()->with('success', 'Booking maked as incomplete!');
    }
}
