<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Controllers\GetEstimateController;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\MovingDetailCategory;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class BookingController extends Controller
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
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'];


        //  Tax Price
        $data['tax_price'] = $this->getEstimateController->getTaxPrice($data['sub_total']);


        // Total amountToPay
        $data['amountToPay'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'] + $data['tax_price'];


        // return a json object
        return response()->json([
            'success' => true,
            'message' => 'Estimate price generated successfully',
            'data' => $data
        ], 200);
    }
}
