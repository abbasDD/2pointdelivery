<?php

namespace App\Http\Controllers\Api\Helper;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetEstimateController;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\BookingReview;
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

        $booking = Booking::select('bookings.id', 'bookings.uuid', 'bookings.client_user_id', 'bookings.helper_user_id', 'bookings.helper_user_id2', 'service_types.name as service_type', 'service_categories.name as service_category', 'priority_settings.name as priority_setting', 'bookings.booking_type', 'bookings.pickup_address', 'bookings.dropoff_address', 'bookings.pickup_latitude', 'bookings.pickup_longitude', 'bookings.dropoff_latitude', 'bookings.dropoff_longitude', 'bookings.booking_date', 'bookings.booking_time', 'bookings.status', 'bookings.receiver_name', 'bookings.receiver_phone', 'bookings.receiver_email', 'bookings.delivery_note', 'bookings.booking_at')
            ->where('bookings.id', $request->id)
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

        $bookingPayment = $this->getHelperFee($booking_id, $booking->booking_type);

        $bookingData = [
            'booking' => $booking,
            'helper_fee' => $bookingPayment['helper_fee'] ?? 0,
            'bookingImages' => $this->getBookingImages($booking_id, $booking->booking_type),
            'helper_user' => [],
            'helper_user2' => [],
            'client_user' => [],
            'helperVehicleData' => [],
            'helperVehicleData2' => []
        ];

        // Check if client data exist
        if ($booking->client_user_id) {
            $bookingData['client_user'] = User::select('users.email', 'clients.first_name', 'clients.last_name', 'clients.profile_image', 'clients.phone_no', 'clients.gender')
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

        // Get helper
        $helper_user = [
            'email' => '',
            'first_name' => '',
            'last_name' => '',
            'phone_no' => '',
            'gender' => '',
            'profile_image' => asset('images/users/default.png'),
        ];
        if ($booking->helper_user_id) {
            $helper_user = User::select('users.email', 'helpers.first_name', 'helpers.last_name', 'helpers.profile_image', 'helpers.gender', 'helpers.phone_no')
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
            'phone_no' => '',
            'gender' => '',
            'profile_image' => asset('images/users/default.png'),
        ];
        if ($booking->helper_user_id2) {
            $helper_user2 = User::select('users.email', 'helpers.first_name', 'helpers.last_name', 'helpers.profile_image', 'helpers.gender', 'helpers.phone_no')
                ->where('users.id', $booking->helper_user_id2)
                ->join('helpers', 'users.id', '=', 'helpers.user_id')
                ->first();
        }
        $bookingData['helper_user2'] = $helper_user2;

        // Get helper vehicle data
        $helperVehicleData = [
            'vehicle_type' => '',
            'vehicle_number' => '',
            'vehicle_make' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'vehicle_year' => '',
            'vehicle_image' => '',
            'description' => '',
        ];
        if ($booking->helper_user_id) {
            $helperVehicleData = HelperVehicle::select('helper_vehicles.vehicle_number', 'helper_vehicles.vehicle_make', 'helper_vehicles.vehicle_model', 'helper_vehicles.vehicle_color', 'helper_vehicles.vehicle_year', 'vehicle_types.name as vehicle_type', 'vehicle_types.image as vehicle_image', 'vehicle_types.description')
                ->join('vehicle_types', 'vehicle_types.id', '=', 'helper_vehicles.vehicle_type_id')
                ->where('user_id', $booking->helper_user_id)->first();
            // Update Image with link
            if ($helperVehicleData->vehicle_image) {
                $helperVehicleData->vehicle_image = asset('images/vehicle_types/' . $helperVehicleData->vehicle_image);
            } else {
                $helperVehicleData->vehicle_image = asset('images/vehicle_types/default.png');
            }
        }
        $bookingData['helperVehicleData'] = $helperVehicleData;

        // Get helper2 vehicle data
        $helperVehicleData2 = [
            'vehicle_type' => '',
            'vehicle_number' => '',
            'vehicle_make' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'vehicle_year' => '',
            'vehicle_image' => '',
            'description' => '',
        ];
        if ($booking->helper_user_id2) {
            $helperVehicleData2 = HelperVehicle::select('helper_vehicles.vehicle_number', 'helper_vehicles.vehicle_make', 'helper_vehicles.vehicle_model', 'helper_vehicles.vehicle_color', 'helper_vehicles.vehicle_year', 'vehicle_types.name as vehicle_type', 'vehicle_types.image as vehicle_image', 'vehicle_types.description')
                ->join('vehicle_types', 'vehicle_types.id', '=', 'helper_vehicles.vehicle_type_id')
                ->where('user_id', $booking->helper_user_id2)->first();
            // Update Image with link
            if ($helperVehicleData2->vehicle_image) {
                $helperVehicleData2->vehicle_image = asset('images/vehicle_types/' . $helperVehicleData2->vehicle_image);
            } else {
                $helperVehicleData2->vehicle_image = asset('images/vehicle_types/default.png');
            }
        }
        $bookingData['helperVehicleData2'] = $helperVehicleData2;

        // Get Boking Review
        $bookingData['booking_review'] = [
            'rating' => '',
            'review' => '',
        ];

        // Get Boking Review
        $booking_review = BookingReview::where('booking_id', $booking->id)->first();
        if ($booking_review) {
            $bookingData['booking_review']['rating'] = $booking_review->rating;
            $bookingData['booking_review']['review'] = $booking_review->review;
        }

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

        // Check if auth user is helper
        if ($booking->helper_id != auth()->user()->id && $booking->helper_user_id2 != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'You are not authorized.',
                'errors' => 'You are not authorized.',
            ], 401);
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


    // In Transit Booking
    public function inTransitBooking(Request $request): JsonResponse
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

        // Check if auth user is helper
        if ($booking->helper_id != auth()->user()->id && $booking->helper_user_id2 != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'You are not authorized.',
                'errors' => 'You are not authorized.',
            ], 401);
        }

        // Check if booking is still in pending status
        if ($booking->status == 'started') {
            // Check if booking->booking_type is delivery
            if ($booking->booking_type == 'delivery') {
                // Get booking delivery data
                $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
                if (!$bookingDelivery) {
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Unable to find Booking Delivery.',
                        'errors' => 'Unable to find Booking Delivery.',
                    ], 422);
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
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Unable to find Booking Moving.',
                        'errors' => 'Unable to find Booking Moving.',
                    ], 422);
                }

                // Update booking moving
                $bookingMoving->start_intransit_at = Carbon::now();
                $bookingMoving->save();
            }


            // Update Booking
            $booking->status = 'in_transit';
            $booking->save();

            // Return success
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Booking in transit successfully',
                'data' => [],
            ], 200);
        }

        // If all else fails return error
        return response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Booking already in transit.',
            'errors' => 'Booking already in transit.',
        ], 422);
    }


    // Complete Booking
    public function completeBooking(Request $request): JsonResponse
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
            'complete_booking_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'signatureCompleted' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
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

        // Check if auth user is helper
        if ($booking->helper_id != auth()->user()->id && $booking->helper_user_id2 != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'You are not authorized.',
                'errors' => 'You are not authorized.',
            ], 401);
        }

        // Check if booking is still in in_transit status
        if ($booking->status != 'in_transit') {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Booking already started.',
                'errors' => 'Booking already started.',
            ], 422);
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

        // Upload signature start image
        if ($request->hasFile('signatureCompleted')) {
            $file = $request->file('signatureCompleted');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $signatureCompleted = $updatedFilename;
        }

        if (!$complete_booking_image || !$signatureCompleted) {
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

            $bookingDelivery->complete_booking_image = $complete_booking_image;
            $bookingDelivery->signatureCompleted = $signatureCompleted;
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
            $bookingMoving->complete_booking_image = $complete_booking_image;
            $bookingMoving->signatureCompleted = $signatureCompleted;
            $bookingMoving->start_booking_at = Carbon::now();
            $bookingMoving->save();
        }

        // Update booking status
        $booking->status = 'completed';
        $booking->save();

        // Return success
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking completed successfully',
            'data' => [],
        ]);
    }

    // In complete Booking
    // In Transit Booking
    public function incompleteBooking(Request $request): JsonResponse
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
            'incomplete_reason' => 'required',
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

        // Check if auth user is helper
        if ($booking->helper_id != auth()->user()->id && $booking->helper_user_id2 != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'You are not authorized.',
                'errors' => 'You are not authorized.',
            ], 401);
        }

        // Check booking status
        if ($booking->status == 'started' || $booking->status == 'in_transit' || $booking->status == 'completed') {
            // Check if booking->booking_type is delivery
            if ($booking->booking_type == 'delivery') {
                // Get booking delivery data
                $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
                if (!$bookingDelivery) {
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Unable to find Booking Delivery.',
                        'errors' => 'Unable to find Booking Delivery.',
                    ], 422);
                }

                // Update booking delivery
                $bookingDelivery->incomplete_reason = $request->incomplete_reason;
                $bookingDelivery->incomplete_booking_at = Carbon::now();
                $bookingDelivery->save();
            }


            // Check if booking->booking_type is moving
            if ($booking->booking_type == 'moving') {
                // Get booking moving data
                $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
                if (!$bookingMoving) {
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Unable to find Booking Moving.',
                        'errors' => 'Unable to find Booking Moving.',
                    ], 422);
                }

                // Update booking moving
                $bookingMoving->incomplete_reason = $request->incomplete_reason;
                $bookingMoving->incomplete_booking_at = Carbon::now();
                $bookingMoving->save();
            }


            // Update Booking
            $booking->status = 'accepted';
            $booking->save();

            // Return success
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Booking Marked as Incomplete successfully',
                'data' => [],
            ], 200);
        }

        // If all else fails return error
        return response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Booking already marked as incomplete.',
            'errors' => 'Booking already marked as incomplete.',
        ], 422);
    }

    // pendingBookings
    public function pendingBookings(Request $request): JsonResponse
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
            ->where('status', 'pending')
            ->get();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Active bookings fetched successfully',
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
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
            ->whereIn('status', ['started', 'in_transit'])
            ->get();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Pending bookings fetched successfully',
            'data' => ['bookings' => $bookings],
        ], 200);
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
            ->where('helper_user_id', auth()->user()->id)
            ->orWhere('helper_user_id2', auth()->user()->id)
            ->whereIn('status', ['completed', 'cancelled'])
            ->get();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking history fetched successfully',
            'data' => ['bookings' => $bookings],
        ], 200);
    }

    // ------------------------------- PRIVATE FUNCTIONS ------------------------------- //

    // Get booking Payment
    private function getHelperFee($booking_id, $booking_type)
    {
        // Get booking payment
        $bookingPayment = [
            'helper_fee' => 0,
        ];

        // Check if booking type is delivery
        if ($booking_type == 'delivery') {
            $bookingDelivery = BookingDelivery::where('booking_id', $booking_id)->where('payment_status', 'unpaid')->first();

            if (!$bookingDelivery) {
                return false;
            }

            $bookingPayment['helper_fee'] = $bookingDelivery->helper_fee;
        }

        // Check if booking type is moving
        if ($booking_type == 'moving') {
            $bookingMoving = BookingMoving::where('booking_id', $booking_id)->where('payment_status', 'unpaid')->first();

            if (!$bookingMoving) {
                return false;
            }

            $bookingPayment['helper_fee'] = $bookingMoving->helper_fee;
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
            'start_booking_image' => $bookingImages['start_booking_image'] ? asset('images/bookings/' . $bookingImages['start_booking_image']) : asset('images/bookings/default.png'),
            'signatureStart' => $bookingImages['signatureStart'] ? asset('images/bookings/' . $bookingImages['signatureStart']) : asset('images/bookings/default.png'),
            'complete_booking_image' => $bookingImages['complete_booking_image'] ? asset('images/bookings/' . $bookingImages['complete_booking_image']) : asset('images/bookings/default.png'),
            'signatureCompleted' => $bookingImages['signatureCompleted'] ? asset('images/bookings/' . $bookingImages['signatureCompleted']) : asset('images/bookings/default.png'),
        ];

        return $bookingImages;
    }

    // Check If Helper has access to change booking status
    private function checkHelperStatus($helper_user_id, $booking_type)
    {
        // Check if helper_user_id is not equal to auth id
        if ($booking_type == 'moving') {
            if ($helper_user_id != auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'You can not in transit this booking.',
                    'errors' => 'You can not in transit this booking.',
                ], 422);
            }
        }

        if ($booking_type == 'moving') {
            if ($helper_user_id != auth()->user()->id) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'You can not in transit this booking.',
                    'errors' => 'You can not in transit this booking.',
                ], 422);
            }
        }
    }
}
