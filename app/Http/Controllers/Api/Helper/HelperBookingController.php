<?php

namespace App\Http\Controllers\Api\Helper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetEstimateController;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\MovingDetailCategory;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

class HelperBookingController extends Controller
{


    // showBooking
    public function getBookingDetails(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        if (!isset($request->id)) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // return response()->json([
        //     'booking_id' => auth()->user()->id
        // ]);

        $booking_id = $request->id;

        $booking = Booking::select('bookings.id', 'bookings.uuid', 'bookings.client_user_id', 'bookings.helper_user_id', 'bookings.helper_user_id2', 'service_types.name as service_type', 'service_categories.name as service_category', 'priority_settings.name as priority_setting', 'bookings.booking_type', 'bookings.pickup_address', 'bookings.dropoff_address', 'bookings.pickup_latitude', 'bookings.pickup_longitude', 'bookings.dropoff_latitude', 'bookings.dropoff_longitude', 'bookings.booking_date', 'bookings.booking_time', 'bookings.status', 'bookings.total_price', 'bookings.receiver_name', 'bookings.receiver_phone', 'bookings.receiver_email', 'bookings.delivery_note', 'bookings.booking_at')
            ->where('bookings.id', $request->id)
            ->where('bookings.client_user_id', auth()->user()->id)
            ->join('service_types', 'bookings.service_type_id', '=', 'service_types.id')
            ->join('service_categories', 'bookings.service_category_id', '=', 'service_categories.id')
            ->join('priority_settings', 'bookings.priority_setting_id', '=', 'priority_settings.id')
            ->first();


        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        $bookingData = [
            'booking' => $booking,
            'bookingPayment' => $this->getBookingPayment($booking_id, $booking->booking_type),
            'bookingImages' => $this->getBookingImages($booking_id, $booking->booking_type),
            'helper_user' => [],
            'helper_user2' => [],
            'client_user' => []
        ];

        // Check if client data exist
        if ($booking->client_user_id) {
            $bookingData['client_user'] = User::select('users.email', 'clients.first_name', 'clients.last_name', 'clients.profile_image')
                ->where('users.id', $booking->client_user_id)
                ->join('clients', 'users.id', '=', 'clients.user_id')
                ->first();

            // Set image with path
            if ($bookingData['client_user']->profile_image) {
                $bookingData['client_user']->profile_image = asset('images/users/' . $bookingData['client_user']->profile_image);
            } else {
                $bookingData['client_user']->profile_image = asset('images/users/default.png');
            }
        }

        // Check if booking payment exist
        if (!$bookingData['bookingPayment']) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // Check if helper accepted the booking
        $helper_user = [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'profile_image' => asset('images/users/default.png'),
        ];
        if ($booking->helper_user_id) {
            $helper_user = User::select('users.email', 'helpers.first_name', 'helpers.last_name', 'helpers.profile_image')
                ->where('users.id', $booking->helper_user_id)
                ->join('helpers', 'users.id', '=', 'helpers.user_id')
                ->first();
        }
        $bookingData['helper_user'] = $helper_user;

        // Check if helper2 accepted the booking
        $helper_user2 = [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'profile_image' => asset('images/users/default.png'),
        ];
        if ($booking->helper_user_id2) {
            $helper_user2 = User::select('users.email', 'helpers.first_name', 'helpers.last_name', 'helpers.profile_image')
                ->where('users.id', $booking->helper_user_id2)
                ->join('helpers', 'users.id', '=', 'helpers.user_id')
                ->first();
        }
        $bookingData['helper_user2'] = $helper_user2;

        // Get helper vehicle data
        $helperVehicleData = [
            'vehicle_number' => '',
            'vehicle_make' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'vehicle_year' => '',
        ];
        if ($booking->helper_user_id) {
            $helperVehicleData = HelperVehicle::select('vehicle_number', 'vehicle_make', 'vehicle_model', 'vehicle_color', 'vehicle_year')
                ->where('user_id', $booking->helper_user_id)->first();
        }
        $bookingData['helperVehicleData'] = $helperVehicleData;

        // Get helper2 vehicle data
        $helperVehicleData2 = [
            'vehicle_number' => '',
            'vehicle_make' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'vehicle_year' => '',
        ];
        if ($booking->helper_user_id2) {
            $helperVehicleData2 = HelperVehicle::select('vehicle_number', 'vehicle_make', 'vehicle_model', 'vehicle_color', 'vehicle_year')
                ->where('user_id', $booking->helper_user_id2)->first();
        }
        $bookingData['helperVehicleData2'] = $helperVehicleData2;

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking details fetched successfully',
            'data' => $bookingData,
        ], 200);
    }


    // Accept Booking
    public function acceptBooking(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if booking exist
        $booking = Booking::where('id', $request->booking_id)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to accept booking.',
                'errors' => 'Unable to accept booking.',
            ], 422);
        }

        // Check is client_user_id is same as auth id
        if ($booking->client_user_id == auth()->user()->id) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'You can not accept your own booking.',
                'errors' => 'You can not accept your own booking.',
            ], 422);
        }

        // Check if booking is still in pending status
        if ($booking->status == 'pending') {

            // Check if booking_type is delivery
            if ($booking->booking_type == 'delivery') {
                // Update booking status
                $booking->helper_user_id = auth()->user()->id;
                $booking->status = 'accepted';
                $booking->save();
            }

            // Check if booking_type is moving
            if ($booking->booking_type == 'moving') {

                // We need 2 Movers for Moving Booking

                // Check if helper_user_id is null then only update user_id of mover1
                if ($booking->helper_user_id == null) {
                    // Update booking status
                    $booking->helper_user_id = auth()->user()->id;
                    $booking->save();
                }
                // if it is not null then store in helper_user_id2 and update booking status
                else {
                    // Check if same user already accepted for user1
                    if ($booking->helper_user_id == auth()->user()->id) {
                        return response()->json([
                            'success' => false,
                            'statusCode' => 422,
                            'message' => 'You have already accepted this booking.',
                            'errors' => 'You have already accepted this booking.',
                        ], 422);
                    }
                    // Update booking status
                    $booking->helper_user_id2 = auth()->user()->id;
                    $booking->status = 'accepted';
                    $booking->save();
                }
            }

            // Return success
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Booking accepted successfully',
                'data' => [],
            ], 200);
        }

        // If all else fails return error
        return response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Booking already accepted.',
            'errors' => 'Booking already accepted.',
        ], 422);
    }

    // Start Booking
    public function startBooking(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Validate request
        $validator = Validator::make($request->all(), [
            'booking_id' => 'required|exists:bookings,id',
            'start_booking_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signatureStart' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if booking exist
        $booking = Booking::where('id', $request->booking_id)->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to accept booking.',
                'errors' => 'Unable to accept booking.',
            ], 422);
        }

        // Check if booking is still in accepted status
        if ($booking->status != 'accepted') {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Booking already started.',
                'errors' => 'Booking already started.',
            ], 422);
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

        if (!$start_booking_image || !$signatureStart) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Error in processing image.',
                'errors' => 'Error in processing image.',
            ], 422);
        }

        // Check if booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
            if (!$bookingDelivery) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Unable to find Booking Delivery.',
                    'errors' => 'Unable to find Booking Delivery.',
                ], 422);
            }

            $bookingDelivery->start_booking_image = $start_booking_image;
            $bookingDelivery->signatureStart = $signatureStart;
            $bookingDelivery->start_booking_at = Carbon::now();
            $bookingDelivery->save();
        }


        // Check if booking_type is moving
        if ($booking->booking_type == 'moving') {
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
            if (!$bookingMoving) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Unable to find Booking Moving.',
                    'errors' => 'Unable to find Booking Moving.',
                ], 422);
            }
            $bookingMoving->start_booking_image = $start_booking_image;
            $bookingMoving->signatureStart = $signatureStart;
            $bookingMoving->start_booking_at = Carbon::now();
            $bookingMoving->save();
        }

        // Update booking status
        $booking->status = 'started';
        $booking->save();

        // Return success
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking started successfully',
            'data' => [],
        ]);
    }

    // getBookingHistory
    public function getBookingHistory(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $bookings = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'booking_date', 'booking_time', 'status', 'total_price')
            ->where('client_user_id', auth()->user()->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->get();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking history fetched successfully',
            'data' => ['bookings' => $bookings],
        ], 200);
    }

    // activeBookings
    public function activeBookings(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $bookings = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'booking_date', 'booking_time', 'status', 'total_price')
            ->where('client_user_id', auth()->user()->id)
            ->whereIn('status', ['pending', 'started', 'in_transit'])
            ->get();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Active bookings fetched successfully',
            'data' => ['bookings' => $bookings],
        ], 200);
    }

    // ------------------------------- PRIVATE FUNCTIONS ------------------------------- //

    // Get booking Payment
    private function getBookingPayment($booking_id, $booking_type)
    {
        // Get booking payment
        $bookingPayment = [
            'insurance_price' => 0,
            'base_price' => 0,
            'distance_price' => 0,
            'priority_price' => 0,
            'vehicle_price' => 0,
            'weight_price' => 0,
            'no_of_room_price' => 0,
            'floor_plan_price' => 0,
            'floor_assess_price' => 0,
            'job_details_price' => 0,
            'sub_total' => 0,
            'tax_price' => 0,
            'total_price' => 0,
        ];

        // Check if booking type is delivery
        if ($booking_type == 'delivery') {
            $bookingDelivery = BookingDelivery::where('booking_id', $booking_id)->where('payment_status', 'unpaid')->first();

            if (!$bookingDelivery) {
                return false;
            }

            $bookingPayment['insurance_price'] = $bookingDelivery->insurance_price;
            $bookingPayment['base_price'] = $bookingDelivery->service_price;
            $bookingPayment['distance_price'] = $bookingDelivery->distance_price;
            $bookingPayment['priority_price'] = $bookingDelivery->priority_price;
            $bookingPayment['vehicle_price'] = $bookingDelivery->vehicle_price;
            $bookingPayment['weight_price'] = $bookingDelivery->weight_price;
            $bookingPayment['sub_total'] = $bookingDelivery->sub_total;
            $bookingPayment['tax_price'] = $bookingDelivery->tax_price;
            $bookingPayment['total_price'] = $bookingDelivery->total_price;
        }

        // Check if booking type is moving
        if ($booking_type == 'moving') {
            $bookingMoving = BookingMoving::where('booking_id', $booking_id)->where('payment_status', 'unpaid')->first();

            if (!$bookingMoving) {
                return false;
            }

            $bookingPayment['base_price'] = $bookingMoving->service_price;
            $bookingPayment['distance_price'] = $bookingMoving->distance_price;
            $bookingPayment['priority_price'] = $bookingMoving->priority_price;
            $bookingPayment['weight_price'] = $bookingMoving->weight_price;
            $bookingPayment['no_of_room_price'] = $bookingMoving->no_of_room_price;
            $bookingPayment['floor_plan_price'] = $bookingMoving->floor_plan_price;
            $bookingPayment['floor_assess_price'] = $bookingMoving->floor_assess_price;
            $bookingPayment['job_details_price'] = $bookingMoving->job_details_price;
            $bookingPayment['sub_total'] = $bookingMoving->sub_total;
            $bookingPayment['tax_price'] = $bookingMoving->tax_price;
            $bookingPayment['total_price'] = $bookingMoving->total_price;
        }

        return $bookingPayment;
    }

    // getBookingImages
    private function getBookingImages($booking_id, $booking_type)
    {
        // Check if booking type is delivery
        if ($booking_type == 'delivery') {
            $bookingImages = BookingDelivery::select('start_booking_image', 'signatureStart', 'complete_booking_image', 'signatureCompleted')
                ->where('booking_id', $booking_id)->first();
        }

        // Check if booking type is moving
        if ($booking_type == 'moving') {
            $bookingImages = BookingMoving::select('start_booking_image', 'signatureStart', 'complete_booking_image', 'signatureCompleted')
                ->where('booking_id', $booking_id)->first();
        }

        $bookingImages = [
            'start_booking_image' => $bookingImages['start_booking_image'] ?? asset('images/bookings/default.png'),
            'signatureStart' => $bookingImages['signatureStart'] ?? asset('images/bookings/default.png'),
            'complete_booking_image' => $bookingImages['complete_booking_image'] ?? asset('images/bookings/default.png'),
            'signatureCompleted' => $bookingImages['signatureCompleted'] ?? asset('images/bookings/default.png'),
        ];

        return $bookingImages;
    }
}
