<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\MovingDetailCategory;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BookingController extends Controller
{
    // newBookingPage1
    public function newBookingPage1()
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
    public function newBookingPage2(Request $request)
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
}
