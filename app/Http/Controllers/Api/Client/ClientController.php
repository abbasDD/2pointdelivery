<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\AddressBook;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\ServiceType;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ClientController extends Controller
{
    // Home Page 
    public function home(): JsonResponse
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

        // Service Image Path
        $responseData['service_image_path'] = asset('images/service_types/');

        // Get list of active services
        $responseData['serviceTypes'] = ServiceType::select('id', 'uuid', 'type', 'name', 'image')
            ->where('is_active', 1)
            ->whereHas('serviceCategories', function ($query) {
                $query->where('is_active', 1);
            })
            // ->where('type', 'delivery')      // uncomment if you want to use only delivery
            ->get();


        // Get latest booking of this user
        $responseData['bookings'] = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'booking_date', 'booking_time', 'status', 'total_price')
            ->where('client_user_id', auth()->user()->id)
            ->orderBy('bookings.created_at', 'desc')
            ->take(10)->get();

        $responseData['personal_details'] = false;
        $responseData['address_details'] = false;
        $responseData['company_details'] = false;

        // Get client details
        $client = Client::where('user_id', auth()->user()->id)->first();
        // Check if client completed its personal details
        if (isset($client) && $client->first_name != null) {
            $responseData['personal_details'] = true;
        }

        // Check if client completed its address details
        if (isset($client) && $client->zip_code != null) {
            $responseData['address_details'] = true;
        }

        if ($client->company_enabled == 1) {
            // Get client company details
            $clientCompany = ClientCompany::where('user_id', auth()->user()->id)->first();
            if (isset($clientCompany) && $clientCompany->legal_name != null) {
                $responseData['company_details'] = true;
            }
        }

        return response()->json([
            'success' => true,
            'message' => 'Home Data fetched successfully',
            'data' => $responseData
        ], 200);
    }

    // getAddressBook
    public function getAddressBook(): JsonResponse
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

        // Get kyc details of logged in user
        $addressBooks = AddressBook::where('user_id', auth()->user()->id)->get();


        return response()->json([
            'success' => true,
            'message' => 'Address Book fetched successfully',
            'data' => $addressBooks
        ], 200);
    }

    // switchToHelper
    public function switchToHelper(): JsonResponse
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

        $userData = [
            'email' => $user->email,
            'referral_code' => $user->referral_code,
            'language_code' => $user->language_code,
            'is_active' => $user->is_active,
            'company_enabled' => null,
            'first_name' => null,
            'middle_name' => null,
            'last_name' => null,
            'profile_image' => asset('images/users/default.png'),
            'personal_details' => false,
            'address_details' => false,
            'company_details' => false,
        ];

        $helper = Helper::where('user_id', $user->id)->first();
        $userData['company_enabled'] = $helper->company_enabled;
        $userData['first_name'] = $helper->first_name;
        $userData['middle_name'] = $helper->middle_name;
        $userData['last_name'] = $helper->last_name;
        $userData['profile_image'] = $helper->profile_image == null ? asset('images/users/default.png') : asset('images/users/' . $helper->profile_image);
        $userData['personal_details'] = false;
        $userData['address_details'] = false;
        $userData['company_details'] = false;

        // Check if helper completed its personal details
        if (isset($helper) && $helper->first_name != null) {
            $userData['personal_details'] = true;
        }

        // Check if helper completed its address details
        if (isset($helper) && $helper->zip_code != null) {
            $userData['address_details'] = true;
        }

        if ($helper->company_enabled == 1) {
            // Get helper company details
            $helperCompany = ClientCompany::where('user_id', auth()->user()->id)->first();
            if (isset($helperCompany) && $helperCompany->legal_name != null) {
                $responseData['company_details'] = true;
            }
        }

        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Switched to helper account successfully',
            'data' => $userData,
        ], 200);
    }

    // getPersonalInfo
    public function getPersonalInfo(): JsonResponse
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
    public function personalUpdate(Request $request): JsonResponse
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
    public function getAddressInfo(): JsonResponse
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
    public function addressUpdate(Request $request): JsonResponse
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

    // passwordUpdate
    public function passwordUpdate(Request $request): JsonResponse
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
            'old_password' => 'required|string',
            'new_password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = auth()->user();

        // Check if old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect',
                'errors' => []
            ], 422);
        }

        // Get the user and update its password
        $user = User::find(auth()->user()->id);

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully',
            'data' => []
        ], 200);
    }

    // getSocialLinks
    public function getSocialLinks(): JsonResponse
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

        // Get social links
        $socialLinks = SocialLink::where('user_id', auth()->user()->id)->get()->pluck('link', 'key')->toArray();

        // return response()->json($socialLinks['facebook']);

        return response()->json([
            'success' => true,
            'message' => 'Social links fetched successfully',
            'data' => [
                'facebook' => $socialLinks['facebook'] ?? 'https://facebook.com/',
                'linkedin' => $socialLinks['linkedin'] ?? 'https://linkedin.com/',
                'instagram' => $socialLinks['instagram'] ?? 'https://instagram.com/',
                'tiktok' => $socialLinks['tiktok'] ?? 'https://tiktok.com/',
            ]
        ], 200);
    }

    // socialLinksUpdate
    public function socialLinksUpdate(Request $request): JsonResponse
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

        // Update social links
        // Get facebook
        $facebookLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'facebook')->first();
        if ($facebookLink) {
            $facebookLink->link = $request->facebook ?? 'https://facebook.com/';
            $facebookLink->save();
        } else {
            $facebookLink = new SocialLink();
            $facebookLink->user_id = auth()->user()->id;
            $facebookLink->user_type = 'client';
            $facebookLink->key = 'facebook';
            $facebookLink->link = $request->facebook ?? 'https://facebook.com/';
            $facebookLink->save();
        }

        // Get linkedin
        $linkedinLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'linkedin')->first();
        if ($linkedinLink) {
            $linkedinLink->link = $request->linkedin ?? 'https://linkedin.com/';
            $linkedinLink->save();
        } else {
            $linkedinLink = new SocialLink();
            $linkedinLink->user_id = auth()->user()->id;
            $linkedinLink->user_type = 'client';
            $linkedinLink->key = 'linkedin';
            $linkedinLink->link = $request->linkedin ?? 'https://linkedin.com/';
            $linkedinLink->save();
        }

        // Get instagram
        $instagramLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'instagram')->first();
        if ($instagramLink) {
            $instagramLink->link = $request->instagram ?? 'https://instagram.com/';
            $instagramLink->save();
        } else {
            $instagramLink = new SocialLink();
            $instagramLink->user_id = auth()->user()->id;
            $instagramLink->user_type = 'client';
            $instagramLink->key = 'instagram';
            $instagramLink->link = $request->instagram ?? 'https://instagram.com/';
            $instagramLink->save();
        }


        // Get tiktok
        $tiktokLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'tiktok')->first();
        if ($tiktokLink) {
            $tiktokLink->link = $request->tiktok ?? 'https://tiktok.com/';
            $tiktokLink->save();
        } else {
            $tiktokLink = new SocialLink();
            $tiktokLink->user_id = auth()->user()->id;
            $tiktokLink->user_type = 'client';
            $tiktokLink->key = 'tiktok';
            $tiktokLink->link = $request->tiktok ?? 'https://tiktok.com/';
            $tiktokLink->save();
        }


        return response()->json([
            'success' => true,
            'message' => 'Social links updated successfully',
            'data' => []
        ], 200);
    }

    // getNotifications
    public function getNotifications(): JsonResponse
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

        // Get only 10
        $notifications = UserNotification::where('receiver_user_id', auth()->user()->id)
            ->where('receiver_user_type', 'client')
            ->orderBy('created_at', 'asc')->take(10)->get();
        $unread_notification = UserNotification::where('receiver_user_id', auth()->user()->id)
            ->where('receiver_user_type', 'client')
            ->where('read', 0)->count();

        $data = [
            'notifications' => $notifications,
            'unread_notification' => $unread_notification
        ];

        // Response

        return response()->json([
            'success' => true,
            'message' => 'Notifications fetched successfully',
            'data' => $data
        ], 200);
    }

    // markAllNotificationsRead
    public function markAllNotificationsRead(): JsonResponse
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

        // Mark all notifications as read
        UserNotification::where('receiver_user_id', auth()->user()->id)->where('receiver_user_type', 'client')->update(['read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read successfully',
            'data' => []
        ], 200);
    }
}