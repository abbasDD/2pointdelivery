<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Helper;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class HelperController extends Controller
{
    // personalUpdate
    function personalUpdate(Request $request): JsonResponse
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

        $validator = Validator::make($request->all(), [
            'account_type' => 'required|in:individual,company',
            'first_name' => 'required|string',
            'last_name' => 'required|string',
            'phone_no' => 'required|string',
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // If helper is found, update its attributes
        $helper = Helper::where('user_id', $user->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = $user->id;
            $helper->save();
        }


        $updated_data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_no' => $request->phone_no,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'company_enabled' => 0
        ];

        // If middle_name is not null

        if ($request->has('middle_name')) {
            $updated_data['middle_name'] = $request->middle_name;
        }

        // If tax_id is not null

        if ($request->has('tax_id')) {
            $updated_data['tax_id'] = $request->tax_id;
        }


        // If account type is is company

        if ($request->account_type == 'company') {
            $updated_data['company_enabled'] = 1;
        }

        // Update helper
        $helper->update($updated_data);


        return response()->json([
            'success' => true,
            'message' => 'Helper Profile updated successfully',
            'data' => []
        ], 200);
    }
}
