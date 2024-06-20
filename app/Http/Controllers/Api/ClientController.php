<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{

    // getPersonalInfo
    function getPersonalInfo(): JsonResponse
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


        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }


        $client = Client::where('user_id', $user->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = $user->id;
            $client->save();
        }

        $clientData = [
            'account_type' => $client->company_enabled ? 'company' : 'individual',
            'first_name' => $client->first_name,
            'middle_name' => $client->middle_name,
            'last_name' => $client->last_name,
            'phone_no' => $client->phone_no,
            'gender' => $client->gender,
            'date_of_birth' => $client->date_of_birth,
            'email' => $user->email,
            'profile_image' => $client->profile_image ? asset('images/users/' . $client->profile_image) : asset('images/users/default.png'),
            'tax_id' => $client->tax_id
        ];


        return response()->json([
            'success' => true,
            'message' => 'Client Profile fetched successfully',
            'data' => $clientData
        ], 200);
    }


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

        // If client is found, update its attributes
        $client = Client::where('user_id', $user->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = $user->id;
            $client->save();
        }


        $updated_data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_no' => $request->phone_no,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'company_enabled' => 0
        ];


        // If account type is is company

        if ($request->account_type == 'company') {
            $updated_data['company_enabled'] = 1;
        }

        // Update client
        $client->update($updated_data);


        return response()->json([
            'success' => true,
            'message' => 'Client Profile updated successfully',
            'data' => []
        ], 200);
    }

    // getAddressInfo
    function getAddressInfo(): JsonResponse
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


        $user = auth()->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }


        $client = Client::where('user_id', $user->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = $user->id;
            $client->save();
        }


        $clientData = [
            'suite' => $client->suite,
            'street' => $client->street,
            'city' => $client->city,
            'state' => $client->state,
            'country' => $client->country,
            'zip_code' => $client->zip_code
        ];

        return response()->json([
            'success' => true,
            'message' => 'Address fetched successfully',
            'data' => $clientData
        ], 200);
    }

    // addressUpdate
    function addressUpdate(Request $request): JsonResponse
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
            'suite' => 'required|string',
            'street' => 'required|string',
            'city' => 'required|string',
            'state' => 'required|string',
            'country' => 'required|string',
            'zip_code' => 'required|string',
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

        // If client is found, update its attributes
        $client = Client::where('user_id', $user->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = $user->id;
            $client->save();
        }


        $updated_data = [
            'suite' => $request->suite,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip_code' => $request->zip_code
        ];


        $client->update($updated_data);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => []
        ], 200);
    }
}
