<?php

namespace App\Http\Controllers\Api\Client;

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
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClientBookingController extends Controller
{
    protected $getEstimateController;

    public function __construct(GetEstimateController $getEstimateController)
    {
        $this->getEstimateController = $getEstimateController;
    }


    // newBookingPage1
    public function newBookingPage1(): JsonResponse
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

        $responseData = [];

        // Get list of active services
        $responseData['serviceTypes'] = ServiceType::select('id', 'uuid', 'type', 'name')
            ->where('is_active', 1)
            ->whereHas('serviceCategories', function ($query) {
                $query->where('is_active', 1);
            })
            // ->where('type', 'delivery')      // uncomment if you want to use only delivery
            ->get();

        // Get delivery priority 
        $responseData['deliveryPriority'] = PrioritySetting::select('id', 'type', 'name', 'price')->where('type', 'delivery')->where('is_active', 1)->get();

        // Get Moving priority
        $responseData['movingPriority'] = PrioritySetting::select('id', 'type', 'name', 'price')->where('type', 'moving')->where('is_active', 1)->get();


        return response()->json([
            'success' => true,
            'message' => 'Booking Page 1 successfully',
            'data' => $responseData
        ], 200);
    }
    // newBookingPage2
    public function newBookingPage2(Request $request): JsonResponse
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

        // Validation
        $validator = Validator::make($request->all(), [
            'service_type_id' => 'required|integer|exists:service_types,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $responseData = [];

        // Get list of active services categories
        $responseData['serviceCategories'] = ServiceCategory::where('service_type_id', $request->service_type_id)
            ->where('is_active', 1)->get();

        // Get No of Rooms 
        $responseData['noOfRooms'] = MovingConfig::select('id', 'type', 'name', 'price')->where('type', 'no_of_rooms')->where('is_active', 1)->get();

        // Floor Plan
        $responseData['floorPlans'] = MovingConfig::select('id', 'type', 'name', 'price')->where('type', 'floor_plan')->where('is_active', 1)->get();

        // Floor Assess
        $responseData['floorAssess'] = MovingConfig::select('id', 'type', 'name', 'price')->where('type', 'floor_assess')->where('is_active', 1)->get();

        // Job Details
        $responseData['jobDetails'] = MovingConfig::select('id', 'type', 'name', 'price')->where('type', 'job_details')->where('is_active', 1)->get();

        // Moving Details
        $responseData['movingDetailCategories'] = MovingDetailCategory::with('movingDetails')->get();


        return response()->json([
            'success' => true,
            'message' => 'Booking Page 2 successfully',
            'data' => $responseData
        ], 200);
    }

    // estimateBooking
    public function estimateBooking(Request $request): JsonResponse
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

        // data to return
        $data = [];

        // Check if service type available for booking
        $serviceType = ServiceType::where('id', $request->selectedServiceTypeID)->where('is_active', 1)->first();
        if (!$serviceType) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Service type not found',
                'errors' => 'Service type not found',
            ], 422);
        }

        // Check selected selectedServiceCategoryUuid is empty
        $serviceCategory = ServiceCategory::where('uuid', $request->selectedServiceCategoryUuid)->where('is_active', 1)->first();
        if (!$serviceCategory) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Service category not found',
                'errors' => 'Service category not found',
            ], 422);
        }

        // Check if priority setting exist
        $prioritySetting = PrioritySetting::where('id', $request->priorityID)->where('is_active', 1)->first();
        if (!$prioritySetting) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Priority setting not found',
                'errors' => 'Priority setting not found',
            ], 422);
        }


        // Get package value and calculate insurance
        $data['insurance_value'] = $this->getEstimateController->getInsuranceValue($request->selectedServiceType, $request->package_value);

        // Get Base Price Value
        $data['base_price'] = $this->getEstimateController->getBasePrice($serviceType->type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);

        // Distance Price
        $data['distance_price'] = $this->getEstimateController->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $request->distance_in_km);

        // Priority Price
        $data['priority_price'] = $prioritySetting->price;

        // Vehicle Price
        $data['vehicle_price'] = $this->getEstimateController->getVehiclePrice($serviceType->type, $serviceCategory->vehicle_type_id, $request->distance_in_km);

        // Weight Price
        $data['weight_price'] = $this->getEstimateController->getWeightPrice($serviceType->type, $serviceCategory, $request->package_weight, $request->package_length, $request->package_width, $request->package_height, $request->selectedMovingDetailsID);


        // If service type is moving
        $data['no_of_room_price'] = 0;
        $data['floor_plan_price'] = 0;
        $data['floor_assess_price'] = 0;
        $data['job_details_price'] = 0;

        if ($serviceType->type == 'moving') {
            // Get Room Price
            $data['no_of_room_price'] = $this->getEstimateController->getNoOfRoomPrice($request->selectedNoOfRoomID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Plan Price
            $data['floor_plan_price'] = $this->getEstimateController->getFloorPlanPrice($request->selectedFloorPlanID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Access Price
            $data['floor_assess_price'] = $this->getEstimateController->getFloorAccessPrice($request->selectedFloorAssessID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Job Details Price
            if ($request->selectedJobDetailsID != '') {
                $data['job_details_price'] = $this->getEstimateController->getJobDetailsPrice($request->selectedJobDetailsID, $serviceCategory, $request->floor_size, $request->no_of_hours);
            }
        }

        // Sub Total
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'] + $data['insurance_value'];


        //  Tax Price
        $data['tax_price'] = $this->getEstimateController->getTaxPrice($data['sub_total']);


        // Total amountToPay
        $data['amountToPay'] = $data['sub_total'] + $data['tax_price'];


        // return a json object
        return response()->json([
            'success' => true,
            'message' => 'Estimate price generated successfully',
            'data' => $data
        ], 200);
    }

    // insuranceBooking
    public function insuranceBooking(Request $request): JsonResponse
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

        $data['insurance_value'] = 0;

        if (!isset($request->value) && $request->value == '') {
            // return a json object
            return response()->json([
                'success' => true,
                'message' => 'Estimate price generated successfully',
                'data' => $data
            ], 200);
        }

        $data['insurance_value'] = $this->getEstimateController->getInsuranceValue('delivery', $request->value);

        // return a json object
        return response()->json([
            'success' => true,
            'message' => 'Estimate price generated successfully',
            'data' => $data
        ], 200);
    }

    // createBooking
    public function createBooking(Request $request): JsonResponse
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
            'selectedServiceTypeID' => 'required|integer|exists:service_types,id',
            'priorityID' => 'required|integer|exists:priority_settings,id',
            'selectedServiceCategoryUuid' => 'required|string|exists:service_categories,uuid',
            'distance_in_km' => 'required|numeric',
            'pickup_address' => 'required|string|max:255',
            'dropoff_address' => 'required|string|max:255',
            'pickup_latitude' => 'required|numeric',
            'pickup_longitude' => 'required|numeric',
            'dropoff_latitude' => 'required|numeric',
            'dropoff_longitude' => 'required|numeric',
            'booking_date' => 'required|date',
            'booking_time' => 'required|date_format:H:i',
            'selectedServiceType' => 'required|in:delivery,moving',
            'receiver_name' => 'required|string|max:255',
            'receiver_phone' => 'required|string|max:255',
            'delivery_note' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // data to return
        $data = [];

        // Check if service type available for booking
        $serviceType = ServiceType::where('id', $request->selectedServiceTypeID)->where('is_active', 1)->first();
        if (!$serviceType) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Service type not found',
                'errors' => 'Service type not found',
            ], 422);
        }

        // Check selected selectedServiceCategoryUuid is empty
        $serviceCategory = ServiceCategory::where('uuid', $request->selectedServiceCategoryUuid)->where('is_active', 1)->first();
        if (!$serviceCategory) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Service category not found',
                'errors' => 'Service category not found',
            ], 422);
        }

        // Check if priority setting exist
        $prioritySetting = PrioritySetting::where('id', $request->priorityID)->where('is_active', 1)->first();
        if (!$prioritySetting) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Priority setting not found',
                'errors' => 'Priority setting not found',
            ], 422);
        }


        // Get package value and calculate insurance
        $data['insurance_value'] = $this->getEstimateController->getInsuranceValue($request->selectedServiceType, $request->package_value);

        // Get Base Price Value
        $data['base_price'] = $this->getEstimateController->getBasePrice($serviceType->type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);

        // Distance Price
        $data['distance_price'] = $this->getEstimateController->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $request->distance_in_km);

        // Priority Price
        $data['priority_price'] = $prioritySetting->price;

        // Vehicle Price
        $data['vehicle_price'] = $this->getEstimateController->getVehiclePrice($serviceType->type, $serviceCategory->vehicle_type_id, $request->distance_in_km);

        // Weight Price
        $data['weight_price'] = $this->getEstimateController->getWeightPrice($serviceType->type, $serviceCategory, $request->package_weight, $request->package_length, $request->package_width, $request->package_height, $request->selectedMovingDetailsID);


        // If service type is moving
        $data['no_of_room_price'] = 0;
        $data['floor_plan_price'] = 0;
        $data['floor_assess_price'] = 0;
        $data['job_details_price'] = 0;

        if ($serviceType->type == 'moving') {
            // Get Room Price
            $data['no_of_room_price'] = $this->getEstimateController->getNoOfRoomPrice($request->selectedNoOfRoomID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Plan Price
            $data['floor_plan_price'] = $this->getEstimateController->getFloorPlanPrice($request->selectedFloorPlanID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Access Price
            $data['floor_assess_price'] = $this->getEstimateController->getFloorAccessPrice($request->selectedFloorAssessID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Job Details Price
            if ($request->selectedJobDetailsID != '') {
                $data['job_details_price'] = $this->getEstimateController->getJobDetailsPrice($request->selectedJobDetailsID, $serviceCategory, $request->floor_size, $request->no_of_hours);
            }
        }

        // Sub Total
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'] + $data['insurance_value'];


        //  Tax Price
        $data['tax_price'] = $this->getEstimateController->getTaxPrice($data['sub_total']);


        // Total amountToPay
        $data['amountToPay'] = $data['sub_total'] + $data['tax_price'];

        // Unique uuid for booking
        $uuid = Str::random(8);

        // Generate uuid and ensure it is unique
        do {
            $uuid = Str::random(8);
            $booking = Booking::where('uuid', $uuid)->first();
        } while ($booking);

        // Create New Booking
        $new_booking = Booking::create([
            'uuid' => $uuid,
            'client_user_id' => auth()->user()->id,
            'service_type_id' => $serviceType->id,
            'priority_setting_id' => $prioritySetting->id,
            'service_category_id' => $serviceCategory->id,
            'booking_type' => $serviceType->type,
            'pickup_address' => $request->pickup_address,
            'dropoff_address' => $request->dropoff_address,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'dropoff_latitude' => $request->dropoff_latitude,
            'dropoff_longitude' => $request->dropoff_longitude,
            'booking_date' => $request->booking_date,
            'booking_time' => $request->booking_time,
            'is_secureship_enabled' => $serviceCategory->is_secureship_enabled,
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'receiver_email' => $request->receiver_email,
            'delivery_note' => $request->delivery_note,
            'total_price' => $data['amountToPay'],
            'booking_at' => now(),
        ]);

        // Return bookingPayment
        $bookingPayment = [];

        if ($serviceType->type == 'delivery') {
            // Create Booking Payment
            $deliveryBooking = BookingDelivery::create([
                'booking_id' => $new_booking->id,
                'distance_price' => number_format((float)$data['distance_price'], 2, '.', ''),
                'weight_price' => number_format((float)$data['weight_price'], 2, '.', ''),
                'priority_price' => number_format((float)$data['priority_price'], 2, '.', ''),
                'service_price' => number_format((float)$data['base_price'], 2, '.', ''),
                'sub_total' => number_format((float)$data['sub_total'], 2, '.', ''),
                'vehicle_price' => number_format((float)$data['vehicle_price'], 2, '.', ''),
                'insurance_price' => number_format((float)$data['insurance_value'], 2, '.', ''), // 'insurance_price'
                'tax_price' => number_format((float)$data['tax_price'], 2, '.', ''),
                'helper_fee' => number_format((float)$serviceCategory->helper_fee, 2, '.', ''),
                'total_price' => number_format((float)$data['amountToPay'], 2, '.', ''),
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);

            // if unable to create deliveryBooking then rollback new_booking
            if (!$deliveryBooking) {
                $new_booking->delete();
                return response()->json(['success' => false, 'data' => 'Unable to create booking']);
            }

            $bookingPayment = $deliveryBooking;
        }

        if ($serviceType->type == 'moving') {
            // Create Booking Payment
            $movingBooking = BookingMoving::create([
                'booking_id' => $new_booking->id,
                'service_price' => number_format((float)$data['base_price'], 2, '.', ''),
                'distance_price' => number_format((float)$data['distance_price'], 2, '.', ''),
                'floor_assess_price' => number_format((float)$data['floor_assess_price'], 2, '.', ''),
                'floor_plan_price' => number_format((float)$data['floor_plan_price'], 2, '.', ''),
                'job_details_price' => number_format((float)$data['job_details_price'], 2, '.', ''),
                'no_of_room_price' => number_format((float)$data['no_of_room_price'], 2, '.', ''),
                'priority_price' => number_format((float)$data['priority_price'], 2, '.', ''),
                'weight_price' => number_format((float)$data['weight_price'], 2, '.', ''),
                'sub_total' => number_format((float)$data['sub_total'], 2, '.', ''),
                'tax_price' => number_format((float)$data['tax_price'], 2, '.', ''),
                'helper_fee' => number_format((float)$serviceCategory->helper_fee, 2, '.', ''),
                'total_price' => number_format((float)$data['amountToPay'], 2, '.', ''),
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);

            // if unable to create movingBooking then rollback new_booking
            if (!$movingBooking) {
                $new_booking->delete();
                return response()->json(['success' => false, 'data' => 'Unable to create booking']);
            }

            $bookingPayment = $movingBooking;
        }

        // Send notification
        // return a json object
        return response()->json([
            'success' => true,
            'message' => 'Booking created successfully',
            'data' => ['booking_id' => $new_booking->id]
        ], 200);
    }

    // getPaymentBooking
    public function getPaymentBooking(Request $request): JsonResponse
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

        // Get booking
        $booking = Booking::where('id', $booking_id)->where('client_user_id', auth()->user()->id)->where('status', 'draft')->first();
        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // Get $bookingPayment
        $bookingPayment = $this->getBookingPayment($booking_id, $booking->booking_type);
        if (!$bookingPayment) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // Return response with booking and booking payment
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking created successfully',
            'data' => ['booking' => $booking, 'booking_payment' => $bookingPayment],
        ], 200);
    }

    // codPaymentBooking
    public function codPaymentBooking(Request $request): JsonResponse
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

        // Check if booking exist on booking_id
        $booking = Booking::where('id', $request->booking_id)->where('client_user_id', auth()->user()->id)->where('status', 'draft')->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // Check if current user is booked by this booking
        if ($booking->client_user_id != auth()->user()->id) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        if ($booking->booking_type == 'delivery') {
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();

            if ($bookingDelivery->payment_status == 'paid') {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Booking already paid.',
                    'errors' => 'Booking already paid.',
                ], 422);
            }
        }

        if ($booking->booking_type == 'moving') {
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();

            if ($bookingMoving->payment_status == 'paid') {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Booking already paid.',
                    'errors' => 'Booking already paid.',
                ], 422);
            }
        }

        // Update booking to paid status

        $booking->update([
            'status' => 'pending',
        ]);

        if ($booking->booking_type == 'delivery') {
            $bookingDelivery->update([
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'payment_at' => Carbon::now(),
            ]);
        }

        if ($booking->booking_type == 'moving') {
            $bookingMoving->update([
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'payment_at' => Carbon::now(),
            ]);
        }

        // Send notification to user

        $userNofitication = UserNotification::create([
            'sender_user_id' => null,
            'receiver_user_id' => auth()->user()->id,
            'receiver_user_type' => 'client',
            'type' => 'booking',
            'reference_id' => $booking->id,
            'title' => 'Booking Payment',
            'content' => 'You have successfully paid for your booking',
            'read' => 0
        ]);

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking paid successfully',
            'data' => [],
        ], 200);
    }


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
                'message' => 'Booking ID is required.',
                'errors' => 'Booking ID is required.',
            ], 422);
        }



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
            'bookingReview' => [],
            'helper_user' => [],
            'helper_user2' => [],
            'client_user' => []
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

        // Check if booking payment exist
        if (!$bookingData['bookingPayment']) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking payment.',
                'errors' => 'Unable to get booking payment.',
            ], 422);
        }

        //  Get helper data
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



    // Track Booking
    public function trackBooking(Request $request): JsonResponse
    { // If token is not valid return error
        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        if (!isset($request->uuid)) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Booking ID is required.',
                'errors' => 'Booking ID is required.',
            ], 422);
        }



        $booking_id = $request->uuid;

        $booking = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'pickup_latitude', 'pickup_longitude', 'dropoff_latitude', 'dropoff_longitude', 'booking_date', 'booking_time', 'status', 'booking_at', 'completed_at')
            ->where('bookings.uuid', $request->uuid)
            ->first();


        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // If booking is draft then show error
        if ($booking->status == 'draft') {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking details.',
                'errors' => 'Unable to get booking details.',
            ], 422);
        }



        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking track data fetched successfully',
            'data' => $booking,
        ], 200);
    }

    // reviewBooking
    public function reviewBooking(Request $request): JsonResponse
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
            'id' => 'required|integer|exists:bookings,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if booking exist on id
        $booking = Booking::where('id', $request->id)->where('client_user_id', auth()->user()->id)->where('status', 'completed')->first();

        if (!$booking) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get booking.',
                'errors' => 'Unable to get booking.',
            ], 422);
        }

        // Check if rating already exist for booking
        $reviewExist = BookingReview::where('booking_id', $booking->id)->where('helper_user_id', $booking->helper_user_id)->first();
        if ($reviewExist) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Review already exist.',
                'errors' => 'Review already exist.',
            ], 422);
        }

        // Get helper user
        $helperUser = Helper::where('user_id', $booking->helper_user_id)->first();

        if (!$helperUser) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Unable to get helper.',
                'errors' => 'Unable to get helper.',
            ], 422);
        }

        // Save review
        $review = new BookingReview();
        $review->booking_id = $booking->id;
        $review->helper_user_id = $booking->helper_user_id;
        $review->helper_id = $helperUser->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        // Response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Booking review saved successfully',
            'data' => [],
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
            'payment_method' => 'cod',
        ];

        // Check if booking type is delivery
        if ($booking_type == 'delivery') {
            $bookingDelivery = BookingDelivery::where('booking_id', $booking_id)->first();

            if (!$bookingDelivery) {
                return false;
            }

            $bookingPayment['insurance_price'] = $bookingDelivery->insurance_price ?? 0;
            $bookingPayment['base_price'] = $bookingDelivery->service_price ?? 0;
            $bookingPayment['distance_price'] = $bookingDelivery->distance_price ?? 0;
            $bookingPayment['priority_price'] = $bookingDelivery->priority_price ?? 0;
            $bookingPayment['vehicle_price'] = $bookingDelivery->vehicle_price ?? 0;
            $bookingPayment['weight_price'] = $bookingDelivery->weight_price ?? 0;
            $bookingPayment['sub_total'] = $bookingDelivery->sub_total;
            $bookingPayment['tax_price'] = $bookingDelivery->tax_price;
            $bookingPayment['total_price'] = $bookingDelivery->total_price;
            $bookingPayment['payment_method'] = $bookingDelivery->payment_method;
        }

        // Check if booking type is moving
        if ($booking_type == 'moving') {
            $bookingMoving = BookingMoving::where('booking_id', $booking_id)->first();

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
            $bookingPayment['payment_method'] = $bookingMoving->payment_method;
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

        if (!$bookingImages) {
            $bookingImageList = [
                'start_booking_image' => asset('images/bookings/default.png'),
                'signatureStart' => asset('images/bookings/default.png'),
                'complete_booking_image' => asset('images/bookings/default.png'),
                'signatureCompleted' => asset('images/bookings/default.png'),
            ];
        }

        $bookingImageList = [
            'start_booking_image' => isset($bookingImages['start_booking_image']) && $bookingImages['start_booking_image'] ? asset('images/bookings/' . $bookingImages['start_booking_image']) : asset('images/bookings/default.png'),
            'signatureStart' => isset($bookingImages['signatureStart']) && $bookingImages['signatureStart'] ? asset('images/bookings/' . $bookingImages['signatureStart']) : asset('images/bookings/default.png'),
            'complete_booking_image' => isset($bookingImages['complete_booking_image']) && $bookingImages['complete_booking_image'] ? asset('images/bookings/' . $bookingImages['complete_booking_image']) : asset('images/bookings/default.png'),
            'signatureCompleted' => isset($bookingImages['signatureCompleted']) && $bookingImages['signatureCompleted'] ? asset('images/bookings/' . $bookingImages['signatureCompleted']) : asset('images/bookings/default.png'),
        ];

        return $bookingImageList;
    }
}
