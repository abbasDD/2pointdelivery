<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\AddressBook;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\ServiceType;
use App\Models\SocialLink;
use App\Models\TeamInvitation;
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
            ->orderBy('bookings.updated_at', 'desc')
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
            'vehicle_details' => false
        ];

        $helper = Helper::where('user_id', $user->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = $user->id;
            $helper->save();
        }
        $userData['company_enabled'] = $helper->company_enabled;
        $userData['first_name'] = $helper->first_name;
        $userData['middle_name'] = $helper->middle_name;
        $userData['last_name'] = $helper->last_name;
        $userData['profile_image'] = $helper->profile_image == null ? asset('images/users/default.png') : asset('images/users/' . $helper->profile_image);
        $userData['personal_details'] = false;
        $userData['address_details'] = false;
        $userData['company_details'] = false;
        $userData['vehicle_details'] = false;

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

        // Check if helpervehicle details exist
        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if (isset($helperVehicle) && $helperVehicle->vehicle_number != null) {
            $responseData['vehicle_details'] = true;
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
            'state_id' => $client->tax_id
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
            'state_id' => 'required|string',
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

        // Set default profile image to null
        $profile_image = $client->profile_image ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/users/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $profile_image = $updatedFilename;
        }

        $updated_data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_no' => $request->phone_no,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'company_enabled' => 0,
            'tax_id' => $request->state_id,
            'profile_image' => $profile_image
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
            'data' => [
                'profile_image' => $profile_image ? asset('images/users/' . $profile_image) : asset('images/users/default.png')
            ]
        ], 200);
    }

    // getCompanyInfo
    public function getCompanyInfo(): JsonResponse
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

        // Get client company
        $clientCompany = ClientCompany::where('user_id', $user->id)->first();

        if (!$clientCompany) {
            // Create
            $clientCompany = new ClientCompany();
            $clientCompany->user_id = $user->id;
            $clientCompany->client_id = $client->id;
            $clientCompany->save();
        }

        $clientCompanyData = [
            'company_logo' => $clientCompany->company_logo ? asset('images/company/' . $clientCompany->company_logo) : asset('images/users/default.png'),
            'company_alias' => $clientCompany->company_alias,
            'legal_name' => $clientCompany->legal_name,
            'industry' => $clientCompany->industry,
            'company_number' => $clientCompany->company_number,
            'gst_number' => $clientCompany->gst_number,
            'website_url' => $clientCompany->website_url,
            'email' => $clientCompany->email,
            'business_phone' => $clientCompany->business_phone,
            'suite' => $clientCompany->suite,
            'street' => $clientCompany->street,
            'city' => $clientCompany->city,
            'state' => $clientCompany->state,
            'country' => $clientCompany->country,
            'zip_code' => $clientCompany->zip_code
        ];


        return response()->json([
            'success' => true,
            'message' => 'Client Company Profile fetched successfully',
            'data' => $clientCompanyData
        ], 200);
    }

    // companyUpdate
    public function companyUpdate(Request $request): JsonResponse
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
            'company_alias' => 'required|string',
            'legal_name' => 'required|string',
            'industry' => 'required|string',
            'company_number' => 'required|string',
            'gst_number' => 'required|string',
            'website_url' => 'required|string',
            'email' => 'required|string',
            'business_phone' => 'required|string',
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

        // Get client company
        $clientCompany = ClientCompany::where('user_id', $user->id)->first();

        if (!$clientCompany) {
            // Create
            $clientCompany = new ClientCompany();
            $clientCompany->user_id = $user->id;
            $clientCompany->client_id = $client->id;
            $clientCompany->save();
        }

        $company_logo = $clientCompany->company_logo ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/company/');
            $file->move($destinationPath, $updatedFilename);

            // Set the company_logo attribute to the new file name
            $company_logo = $updatedFilename;
        }


        $updated_data = [
            'company_alias' => $request->company_alias,
            'legal_name' => $request->legal_name,
            'industry' => $request->industry,
            'company_number' => $request->company_number,
            'gst_number' => $request->gst_number,
            'website_url' => $request->website_url,
            'email' => $request->email,
            'business_phone' => $request->business_phone,
            'suite' => $request->suite,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip_code' => $request->zip_code,
            'company_logo' => $company_logo
        ];



        // Update clientCompany
        $clientCompany->update($updated_data);


        return response()->json([
            'success' => true,
            'message' => 'Client Company Profile updated successfully',
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


    // Teams

    // getTeams
    public function getInvitedUsers(): JsonResponse
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

        $acceptedInvites = TeamInvitation::where('inviter_id', $user->id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Teams fetched successfully',
            'data' => $acceptedInvites
        ], 200);
    }

    public function inviteTeamMember(Request $request): JsonResponse
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
            'invitee_email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $invitee = User::where('email', $request->invitee_email)->where('user_type', 'user')->first();

        if (!$invitee) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'User not found',
                'data' => []
            ], 404);
        }

        $invitationData = [];

        // Check if invitee exists
        if ($invitee) {
            // Check is user invited himself
            if ($invitee->id == Auth::id()) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'You cannot invite yourself',
                    'data' => []
                ], 422);
            }

            // Check is user already invited
            if (TeamInvitation::where('invitee_id', $invitee->id)->where('inviter_id', Auth::id())->exists()) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'You have already invited this user',
                    'data' => []
                ], 422);
            }

            $invitationData['invitee_id'] = $invitee->id;
        }

        // Check if invitee email is not a user

        $invitationData['inviter_id'] = Auth::id();
        $invitationData['invitee_email'] = $invitee->email;

        // dd($invitationData);

        $teamInvitation = TeamInvitation::create($invitationData);

        // Send Notification
        UserNotification::create([
            'sender_user_id' => auth()->user()->id,
            'receiver_user_id' => $invitee->id,
            'receiver_user_type' => 'client',
            'reference_id' => $teamInvitation->id,
            'type' => 'team_invitation',
            'title' => 'Team Invitation',
            'content' => 'You have been invited to join the team',
            'read' => 0
        ]);


        // Response

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Team invitation sent successfully',
            'data' => []
        ], 200);
    }

    // removeTeamMember

    public function removeTeamMember(Request $request): JsonResponse
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
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }
        // Check is user already invited
        if (TeamInvitation::where('id', $request->id)->where('inviter_id', Auth::id())->exists()) {

            TeamInvitation::where('id', $request->id)->delete();

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'You have successfully removed this user',
                'data' => []
            ], 200);
        }


        // 
        return response()->json([
            'success' => false,
            'statusCode' => 422,
            'message' => 'Unable to find this invitation',
            'data' => []
        ], 422);
    }

    // Invitaions

    // getInvitations
    public function getInvitations(): JsonResponse
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

        $user = Auth::user();
        $invitations = TeamInvitation::select('invitee_id', 'inviter_id', 'users.email as inviter_email', 'team_invitations.*')
            ->join('users', 'team_invitations.inviter_id', '=', 'users.id')
            ->where('invitee_id', $user->id)
            ->get();


        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Invitations fetched successfully',
            'data' => $invitations
        ], 200);
    }

    // acceptInviation
    public function acceptInviation(Request $request): JsonResponse
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
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // get team invitation

        $teamInvitation = TeamInvitation::where('id', $request->id)->where('invitee_id', Auth::id())->first();
        if (!$teamInvitation) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Invitation not found',
                'data' => []
            ], 404);
        }

        TeamInvitation::where('id', $request->id)->update(['status' => 'accepted']);

        // Response

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Invitation accepted successfully',
            'data' => []
        ], 200);
    }

    // declineInvitation
    public function declineInvitation(Request $request): JsonResponse
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
            'id' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // get team invitation

        $teamInvitation = TeamInvitation::where('id', $request->id)
            ->where('invitee_id', Auth::id())
            ->where('status', 'pending')
            ->first();

        if (!$teamInvitation) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Invitation not found',
                'data' => []
            ], 404);
        }

        TeamInvitation::where('id', $request->id)->update(['status' => 'declined']);

        // Response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Invitation declined successfully',
            'data' => []
        ], 200);
    }
}
