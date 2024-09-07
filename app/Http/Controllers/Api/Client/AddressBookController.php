<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\AddressBookResource;
use Illuminate\Http\Request;
use App\Models\AddressBook;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Validator;

class AddressBookController extends Controller
{
    // getAddressBook
    public function getAddressBook(): JsonResponse
    {
        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get kyc details of logged in user
        $addressBooks = AddressBook::where('user_id', Auth::user()->id)->get();


        return response()->json([
            'success' => true,
            'message' => 'Address Book fetched successfully',
            'data' => AddressBookResource::collection($addressBooks),
        ], 200);
    }

    // updateAddressBook
    public function updateAddressBook(Request $request): JsonResponse
    {
        // If token is not valid return error
        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // validate
        $validator = Validator::make($request->all(), [
            'id' => 'required',
            'pickup_address' => 'required',
            'dropoff_address' => 'required',
            'pickup_latitude' => 'required',
            'pickup_longitude' => 'required',
            'dropoff_latitude' => 'required',
            'dropoff_longitude' => 'required',
            'receiver_name' => 'required',
            'receiver_phone' => 'required',
            'receiver_email' => 'nullable',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Update address book
        $addressBook = AddressBook::where('user_id', Auth::user()->id)->where('id', $request->id)->first();
        if (!$addressBook) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Address Book not found',
                'errors' => 'Address Book not found',
            ], 422);
        }

        $addressBook->pickup_address = $request->pickup_address;
        $addressBook->dropoff_address = $request->dropoff_address;
        $addressBook->pickup_latitude = $request->pickup_latitude;
        $addressBook->pickup_longitude = $request->pickup_longitude;
        $addressBook->dropoff_latitude = $request->dropoff_latitude;
        $addressBook->dropoff_longitude = $request->dropoff_longitude;
        $addressBook->receiver_name = $request->receiver_name;
        $addressBook->receiver_phone = $request->receiver_phone;
        $addressBook->receiver_email = $request->receiver_email;
        $addressBook->save();


        return response()->json([
            'success' => true,
            'message' => 'Address Book updated successfully',
            'data' => new AddressBookResource($addressBook),
        ], 200);
    }
}
