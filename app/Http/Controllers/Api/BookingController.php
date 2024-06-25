<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\PrioritySetting;
use App\Models\ServiceType;
use Illuminate\Http\Request;

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
}
