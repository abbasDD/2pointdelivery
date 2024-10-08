<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\Chat;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\Message;
use App\Models\ServiceType;
use App\Models\SocialLink;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSwitch;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class ClientController extends Controller
{
    // Get Client Profile
    public function index(): JsonResponse
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

        $user = User::find(Auth::user()->id);
        $userData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'referral_code' => $user->referral_code,
            'language_code' => $user->language_code,
            'is_active' => $user->is_active,
            'company_enabled' => null,
            'first_name' => null,
            'middle_name' => null,
            'last_name' => null,
            'profile_image' => asset('images/users/default.png'),
            'thumbnail' => asset('images/users/default.png'),
            'personal_details' => false,
            'address_details' => false,
            'company_details' => false,
            'vehicle_details' => false,
            'is_notified' => 0
        ];

        $client = Client::where('user_id', $user->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = $user->id;
            $client->save();
        }
        $userData['company_enabled'] = $client->company_enabled;
        $userData['first_name'] = $client->first_name;
        $userData['middle_name'] = $client->middle_name;
        $userData['last_name'] = $client->last_name;
        $userData['profile_image'] = $client->profile_image == null ? asset('images/users/default.png') : asset('images/users/' . $client->profile_image);
        $userData['personal_details'] = false;
        $userData['address_details'] = false;
        $userData['company_details'] = false;
        $userData['is_notified'] = $client->is_notified;

        // Check if client completed its personal details
        if (isset($client) && $client->first_name != null) {
            $userData['personal_details'] = true;
        }

        // Check if client completed its address details
        if (isset($client) && $client->zip_code != null) {
            $userData['address_details'] = true;
        }

        if ($client->company_enabled == 1) {
            // Get client company details
            $userData['company_details'] = true;
            $clientCompany = ClientCompany::where('user_id', Auth::user()->id)->first();
            if (isset($clientCompany) && $clientCompany->legal_name != null) {
                $userData['company_details'] = true;
            }
        }

        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Client profile account fetched successfully',
            'data' => $userData,
        ], 200);
    }

    // Home Page 
    public function home(): JsonResponse
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
            ->where('client_user_id', Auth::user()->id)
            ->whereIn('status', ['draft', 'pending'])
            ->orderBy('bookings.updated_at', 'desc')
            ->take(10)->get();

        $responseData['personal_details'] = false;
        $responseData['address_details'] = false;
        $responseData['company_details'] = false;

        // Get client details
        $client = Client::where('user_id', Auth::user()->id)->first();
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
            $clientCompany = ClientCompany::where('user_id', Auth::user()->id)->first();
            if (isset($clientCompany) && $clientCompany->legal_name != null) {
                $responseData['company_details'] = true;
            }
        }

        // Unread notification count for user
        $responseData['unreadNotificationCount'] = UserNotification::where('receiver_user_id', Auth::user()->id)->where('receiver_user_type', 'client')->where('read', 0)->count() ?? 0;

        return response()->json([
            'success' => true,
            'message' => 'Home Data fetched successfully',
            'data' => $responseData
        ], 200);
    }



    // switchToHelper
    public function switchToHelper(): JsonResponse
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

        $user = User::find(Auth::user()->id);

        $userData = [
            'user_id' => $user->id,
            'email' => $user->email,
            'referral_code' => $user->referral_code,
            'language_code' => $user->language_code,
            'is_active' => $user->is_active,
            'company_enabled' => null,
            'first_name' => null,
            'middle_name' => null,
            'last_name' => null,
            'profile_image' => asset('images/users/default.png'),
            'thumbnail' => asset('images/users/default.png'),
            'personal_details' => false,
            'address_details' => false,
            'company_details' => false,
            'vehicle_details' => false,
            'is_approved' => 0,
            'is_notified' => 0
        ];

        // Get Helper data from DB
        $helper = Helper::where('user_id', Auth::user()->id)->first();

        // If helper not found
        if (!$helper) {
            // Check if Client is created with same id
            $client = Client::where('user_id', Auth::user()->id)->first();

            // If client is found then duplicate data to helper
            if ($client) {

                // Check if client first name and last name is not null
                if ($client->first_name == null || $client->last_name == null || $client->city == null) {
                    // return redirect()->route('client.profile')->with('error', 'Please fill your client detail first');
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Please fill your client detail first',
                        'errors' => 'Please fill your client detail first',
                    ], 422);
                }

                $helper = Helper::create([
                    'user_id' => Auth::user()->id,
                    'company_enabled' => $client->company_enabled ?? 0,
                    'first_name' => $client->first_name ?? '',
                    'middle_name' => $client->middle_name ?? '',
                    'last_name' => $client->last_name ?? '',
                    'gender' => $client->gender ?? '',
                    'date_of_birth' => $client->date_of_birth ?? '',
                    'tax_id' => $client->tax_id ?? '',
                    'profile_image' => $client->profile_image ?? null,
                    'thumbnail' => $client->thumbnail ?? null,
                    'phone_no' => $client->phone_no ?? '',
                    'suite' => $client->suite ?? '',
                    'street' => $client->street ?? '',
                    'city' => $client->city     ?? '',
                    'state' => $client->state ?? '',
                    'country' => $client->country ?? '',
                    'zip_code' => $client->zip_code ?? '',
                ]);
            }
            // If not then create a simple helper
            else {
                $helper = Helper::create([
                    'user_id' => Auth::user()->id,
                ]);
            }
        }

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
        $userData['is_approved'] = $helper->is_approved;
        $userData['is_notified'] = $helper->is_notified;

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
            $helperCompany = ClientCompany::where('user_id', Auth::user()->id)->first();
            if (isset($helperCompany) && $helperCompany->legal_name != null) {
                $userData['company_details'] = true;
            }
        }

        // Check if helpervehicle details exist
        $helperVehicle = HelperVehicle::where('user_id', Auth::user()->id)->first();
        if (isset($helperVehicle) && $helperVehicle->vehicle_number != null) {
            $userData['vehicle_details'] = true;
        }

        // Make helper_enabled 1
        $user->helper_enabled = 1;
        $user->save();

        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Switched to helper account successfully',
            'data' => $userData,
        ], 200);
    }

    // toggleNotification
    public function toggleNotification(Request $request): JsonResponse
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

        $client = Client::where('user_id', Auth::user()->id)->first();

        if (!$client) {
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Unable to find client.',
                'errors' => 'Unable to find client.',
            ], 401);
        }
        // Toggle is_notified field
        $client->is_notified = $client->is_notified == 1 ? 0 : 1;
        $client->save();


        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Notification toggled successfully',
            'data' => $client->is_notified
        ]);
    }

    // getPersonalInfo
    public function getPersonalInfo(): JsonResponse
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


        $user = User::find(Auth::user()->id);

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
            'thumbnail' => $client->thumbnail ? asset('images/users/thumbnail/' . $client->thumbnail) : asset('images/users/default.png'),
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

        if (!Auth::user()) {
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
            'gender' => 'required|in:male,female,other',
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


        $user = User::find(Auth::user()->id);

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
        $thumbnail = $client->thumbnail ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('profile_image')) {
            $image = Image::read($request->file('profile_image'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $destinationPath = public_path('images/users/');
            $image->save($destinationPath . $imageName);

            // Generate Thumbnail Image Upload on Folder Code
            $destinationPathThumbnail = public_path('images/users/thumbnail/');
            $image->resize(100, 100);
            $image->save($destinationPathThumbnail . $imageName);

            $profile_image = $imageName;
            $thumbnail = $imageName;
        }

        $updated_data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'phone_no' => $request->phone_no,
            'gender' => $request->gender,
            'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
            'company_enabled' => 0,
            'tax_id' => $request->state_id,
            'profile_image' => $profile_image,
            'thumbnail' => $thumbnail
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
                'profile_image' => $thumbnail ? asset('images/users/thumbnail/' . $thumbnail) : asset('images/users/default.png')
            ]
        ], 200);
    }

    // getCompanyInfo
    public function getCompanyInfo(): JsonResponse
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


        $user = User::find(Auth::user()->id);

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

        if (!Auth::user()) {
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


        $user = User::find(Auth::user()->id);

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
        $thumbnail = $clientCompany->thumbnail ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('company_logo')) {
            $image = Image::read($request->file('company_logo'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('company_logo')->getClientOriginalExtension();
            $destinationPath = public_path('images/company/');
            $image->save($destinationPath . $imageName);

            // Generate Thumbnail Image Upload on Folder Code
            $destinationPathThumbnail = public_path('images/company/thumbnail/');
            $image->resize(100, 100);
            $image->save($destinationPathThumbnail . $imageName);

            $company_logo = $imageName;
            $thumbnail = $imageName;
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
            'company_logo' => $company_logo,
            'thumbnail' => $thumbnail
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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }


        $user = User::find(Auth::user()->id);

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

        if (!Auth::user()) {
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


        $user = User::find(Auth::user()->id);

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

        if (!Auth::user()) {
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

        $user = User::find(Auth::user()->id);

        // Check if old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect',
                'errors' => []
            ], 422);
        }

        // Get the user and update its password
        $user = User::find(Auth::user()->id);

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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get social links
        $socialLinks = SocialLink::where('user_id', Auth::user()->id)->get()->pluck('link', 'key')->toArray();

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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Update social links
        // Get facebook
        $facebookLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'facebook')->first();
        if ($facebookLink) {
            $facebookLink->link = $request->facebook ?? 'https://facebook.com/';
            $facebookLink->save();
        } else {
            $facebookLink = new SocialLink();
            $facebookLink->user_id = Auth::user()->id;
            $facebookLink->user_type = 'client';
            $facebookLink->key = 'facebook';
            $facebookLink->link = $request->facebook ?? 'https://facebook.com/';
            $facebookLink->save();
        }

        // Get linkedin
        $linkedinLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'linkedin')->first();
        if ($linkedinLink) {
            $linkedinLink->link = $request->linkedin ?? 'https://linkedin.com/';
            $linkedinLink->save();
        } else {
            $linkedinLink = new SocialLink();
            $linkedinLink->user_id = Auth::user()->id;
            $linkedinLink->user_type = 'client';
            $linkedinLink->key = 'linkedin';
            $linkedinLink->link = $request->linkedin ?? 'https://linkedin.com/';
            $linkedinLink->save();
        }

        // Get instagram
        $instagramLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'instagram')->first();
        if ($instagramLink) {
            $instagramLink->link = $request->instagram ?? 'https://instagram.com/';
            $instagramLink->save();
        } else {
            $instagramLink = new SocialLink();
            $instagramLink->user_id = Auth::user()->id;
            $instagramLink->user_type = 'client';
            $instagramLink->key = 'instagram';
            $instagramLink->link = $request->instagram ?? 'https://instagram.com/';
            $instagramLink->save();
        }


        // Get tiktok
        $tiktokLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'tiktok')->first();
        if ($tiktokLink) {
            $tiktokLink->link = $request->tiktok ?? 'https://tiktok.com/';
            $tiktokLink->save();
        } else {
            $tiktokLink = new SocialLink();
            $tiktokLink->user_id = Auth::user()->id;
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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get only 10
        $notifications = UserNotification::where('receiver_user_id', Auth::user()->id)
            ->where('receiver_user_type', 'client')
            ->orderBy('created_at', 'asc')->take(10)->get();
        $unread_notification = UserNotification::where('receiver_user_id', Auth::user()->id)
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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Mark all notifications as read
        UserNotification::where('receiver_user_id', Auth::user()->id)->where('receiver_user_type', 'client')->update(['read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Notifications marked as read successfully',
            'data' => []
        ], 200);
    }

    // markNotificationsRead
    public function markNotificationsRead(Request $request): JsonResponse
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

        // Mark all notifications as read
        UserNotification::where('receiver_user_id', Auth::user()->id)
            ->where('id', $request->id)
            ->where('receiver_user_type', 'client')
            ->update(['read' => 1]);

        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read successfully',
            'data' => []
        ], 200);
    }


    // Teams

    // getTeams
    public function getInvitedUsers(): JsonResponse
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

        $user = User::find(Auth::user()->id);

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

        if (!Auth::user()) {
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
            'sender_user_id' => Auth::user()->id,
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

        if (!Auth::user()) {
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


    // Switch to User
    public function switchUser(Request $request): JsonResponse
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

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors(),
            ], 422);
        }

        $userId = $request->user_id;

        $currentUser = Auth::user();

        $invitation = TeamInvitation::where('invitee_id', $currentUser->id)
            ->where('inviter_id', $userId)
            ->where('status', 'accepted')
            ->first();

        if ($invitation) {
            // Check if userswitch already exist
            if (UserSwitch::where('original_user_id', $currentUser->id)->where('switched_user_id', $userId)->exists()) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'You have already switched to this user',
                    'errors' => 'You have already switched to this user',
                ]);
            }

            // Store the switch in the database
            UserSwitch::create([
                'original_user_id' => $currentUser->id,
                'switched_user_id' => $userId,
                'platform' => 'api',
            ]);

            // Generate a new token for the user being switched to
            $switchedUser = User::find($userId);
            if (!$switchedUser) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 404,
                    'message' => 'User not found.',
                    'errors' => 'User not found.',
                ]);
            }

            $tokenResult = $switchedUser->createToken('2PointDeliveryJWTAuthenticationToken');
            $newToken = $tokenResult->accessToken;

            // Return the new token
            return response()->json([
                'success' => true,
                'message' => 'Switched user successfully',
                'token' => $newToken,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'statusCode' => 403,
            'message' => 'Unable to find invitation.',
            'errors' => 'Unable to find invitation.',
        ], 403);
    }

    // Switch  to self user
    public function switchToSelf(): JsonResponse
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

        // Find the switch record
        $switchRecord = UserSwitch::where('switched_user_id', Auth::user()->id)->first();

        if (!$switchRecord) {
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Original user ID not found.',
                'errors' => 'Original user ID not found.',
            ], 400);
        }

        // Revoke the current token
        Auth::user()->token()->revoke();

        // Log in using the original user ID
        $originalUser = User::find($switchRecord->original_user_id);
        if (!$originalUser) {
            return response()->json([
                'success' => false,
                'statusCode' => 400,
                'message' => 'Original user not found.',
                'errors' => 'Original user not found.',
            ], 400);
        }

        // Generate a new token for the original user
        $tokenResult = $originalUser->createToken('2PointDeliveryJWTAuthenticationToken');
        $newToken = $tokenResult->accessToken;

        // Delete the switch record
        $switchRecord->delete();

        // Return the new token
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Switched back to original user successfully',
            'token' => $newToken,
        ], 200);
    }

    // Invitaions

    // getInvitations
    public function getInvitations(): JsonResponse
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

        $user = User::find(Auth::user()->id);
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

        if (!Auth::user()) {
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

        if (!Auth::user()) {
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

    // Chats
    public function getChatList()
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

        // Retrieve the user
        $user = User::findOrFail(Auth::user()->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Get the user's chats along with the other user in the chat
        $chats = $user->chats()->with('otherUser')->get();

        foreach ($chats as $chat) {
            $chat->last_message = $chat->messages()->latest()->first();
            if ($chat->user1_id == Auth::user()->id) {
                $otherUser = User::findOrFail($chat->user2_id);
                // Check if user is admin
                if ($otherUser->user_type == 'admin') {
                    // $chat->otherUserInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    // if ($chat->otherUserInfo) {
                    //     $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    // } else {
                    //     $chat->otherUserInfo->profile_image = asset('images/users/default.png');
                    // }
                    $otherUserInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    if ($otherUserInfo) {
                        $chat->otherUserInfo = $otherUserInfo;
                        $chat->otherUserInfo->profile_image = $otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $otherUserInfo->thumbnail) : asset('images/users/default.png');
                    } else {
                        // Create admin
                        Admin::create([
                            'user_id' => $otherUser->id,
                            'first_name' => 'Admin',
                            'last_name' => 'Admin',
                        ]);

                        $chat->otherUserInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    }
                } else {
                    if ($otherUser->client_enabled) {
                        // $chat->otherUserInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        // if ($chat->otherUserInfo) {
                        //     $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        // } else {
                        //     $chat->otherUserInfo->profile_image = asset('images/users/default.png');
                        // }
                        $otherUserInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        if ($otherUserInfo) {
                            $chat->otherUserInfo = $otherUserInfo;
                            $chat->otherUserInfo->profile_image = $otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $otherUserInfo->thumbnail) : asset('images/users/default.png');
                        } else {
                            // Create client
                            Client::create([
                                'user_id' => $otherUser->id,
                                'first_name' => 'Client',
                                'last_name' => 'Client',
                            ]);
                            $chat->otherUserInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                            $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        }
                    } else {
                        // $chat->otherUserInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        // if ($chat->otherUserInfo) {
                        //     $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        // } else {
                        //     $chat->otherUserInfo->profile_image = asset('images/users/default.png');
                        // }
                        $otherUserInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        if ($otherUserInfo) {
                            $chat->otherUserInfo = $otherUserInfo;
                            $chat->otherUserInfo->profile_image = $otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $otherUserInfo->thumbnail) : asset('images/users/default.png');
                        } else {
                            // Create helper
                            Helper::create([
                                'user_id' => $otherUser->id,
                                'first_name' => 'Helper',
                                'last_name' => 'Helper',
                            ]);
                            $chat->otherUserInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                            $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        }
                    }
                }
            } else {
                $otherUser = User::findOrFail($chat->user1_id);
                // Check if user is admin
                if ($otherUser->user_type == 'admin') {
                    // $chat->otherUserInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    // if ($chat->otherUserInfo) {
                    //     $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    // } else {
                    //     $chat->otherUserInfo->profile_image = asset('images/users/default.png');
                    // }
                    $otherUserInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    if ($otherUserInfo) {
                        $chat->otherUserInfo = $otherUserInfo;
                        $chat->otherUserInfo->profile_image = $otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $otherUserInfo->thumbnail) : asset('images/users/default.png');
                    } else {
                        // Create admin
                        Admin::create([
                            'user_id' => $otherUser->id,
                            'first_name' => 'Admin',
                            'last_name' => 'Admin',
                        ]);
                        $chat->otherUserInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    }
                } else {
                    if ($otherUser->client_enabled) {
                        // $chat->otherUserInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        // if ($chat->otherUserInfo) {
                        //     $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        // } else {
                        //     $chat->otherUserInfo->profile_image = asset('images/users/default.png');
                        // }
                        $otherUserInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        if ($otherUserInfo) {
                            $chat->otherUserInfo = $otherUserInfo;
                            $chat->otherUserInfo->profile_image = $otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $otherUserInfo->thumbnail) : asset('images/users/default.png');
                        } else {
                            // Create client
                            Client::create([
                                'user_id' => $otherUser->id,
                                'first_name' => 'Client',
                                'last_name' => 'Client',
                            ]);
                            $chat->otherUserInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                            $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        }
                    } else {
                        $chat->otherUserInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        // if ($chat->otherUserInfo) {
                        //     $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        // } else {
                        //     $chat->otherUserInfo->profile_image = asset('images/users/default.png');
                        // }
                        $otherUserInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        if ($otherUserInfo) {
                            $chat->otherUserInfo = $otherUserInfo;
                            $chat->otherUserInfo->profile_image = $otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $otherUserInfo->thumbnail) : asset('images/users/default.png');
                        } else {
                            // Create helper
                            Helper::create([
                                'user_id' => $otherUser->id,
                                'first_name' => 'Helper',
                                'last_name' => 'Helper',
                            ]);
                            $chat->otherUserInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $otherUser->id)->first();
                            $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                        }
                    }
                }
            }
        }

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Chats retrieved successfully',
            'data' => $chats
        ], 200);
    }

    // Create Chat
    public function createChat(Request $request)
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

        // Retrieve the user
        $user = User::findOrFail($request->user_id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }


        if ($user->user_type == 'admin') {
            $userInfo = Admin::select('first_name', 'last_name', 'thumbnail')->where('user_id', $user->id)->first();
            // profile_image
            $userInfo->profile_image = $userInfo->thumbnail ? asset('images/users/thumbnail/' . $userInfo->thumbnail) : asset('images/users/default.png');
        } else {
            // Get User detail as per user type
            if ($user->client_enabled == 1) {
                $userInfo = Client::select('first_name', 'last_name', 'thumbnail')->where('user_id', $user->id)->first();
                // profile_image
                $userInfo->profile_image = $userInfo->thumbnail ? asset('images/users/thumbnail/' . $userInfo->thumbnail) : asset('images/users/default.png');
            }

            if ($user->helper_enabled == 1) {
                $userInfo = Helper::select('first_name', 'last_name', 'thumbnail')->where('user_id', $user->id)->first();
                // profile_image
                $userInfo->profile_image = $userInfo->thumbnail ? asset('images/users/thumbnail/' . $userInfo->thumbnail) : asset('images/users/default.png');
            }
        }



        // Check chat between users already exists
        $chatExists = Chat::where('user1_id', $user->id)->where('user2_id', Auth::user()->id)->orWhere('user1_id', Auth::user()->id)->where('user2_id', $user->id)->first();

        if ($chatExists) {
            // return response()->json(['success' => true, 'chat_id' => $chatExists->id, 'userInfo' => $userInfo, 'message' => 'Chat already exists']);
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'Chats already exists',
                'data' => [
                    'chat_id' => $chatExists->id,
                    'userInfo' => $userInfo
                ]
            ], 200);
        }
        // Create chat between user_id and current user
        $chat = new Chat();
        $chat->user1_id = $user->id;
        $chat->user2_id = Auth::user()->id;
        $chat->save();

        // return response()->json(['success' => true, 'chat_id' => $chat->id, 'userInfo' => $userInfo, 'message' => 'Chat created successfully']);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Chats created successfully',
            'data' => [
                'chat_id' => $chat->id,
                'userInfo' => $userInfo
            ]
        ]);
    }

    public function getUserChat(Request $request)
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

        // Retrieve the user
        $user = User::findOrFail(Auth::user()->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $chat = Chat::find($request->chat_id);
        if (!$chat) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Chat not found.',
                'errors' => 'Chat not found',
            ], 422);
        }
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        if ($chat->user1_id == Auth::user()->id) {
            $otherUser = User::findOrFail($chat->user2_id);
            // Check if user is admin
            if ($otherUser->user_type == 'admin') {
                $otherUserInfo = Admin::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                // profile_image
                $otherUserInfo->profile_image = asset('images/users/' . $otherUserInfo->profile_image);
            } else {
                if ($otherUser->client_enabled) {
                    $otherUserInfo = Client::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    // profile_image
                    $otherUserInfo->profile_image = asset('images/users/' . $otherUserInfo->profile_image);
                } else {
                    $otherUserInfo = Helper::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    // profile_image
                    $otherUserInfo->profile_image = asset('images/users/' . $otherUserInfo->profile_image);
                }
            }
        } else {
            $otherUser = User::findOrFail($chat->user1_id);

            // Check if user is admin
            if ($otherUser->user_type == 'admin') {
                $otherUserInfo = Admin::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                // profile_image
                $otherUserInfo->profile_image = asset('images/users/' . $otherUserInfo->profile_image);
            } else {
                if ($otherUser->client_enabled) {
                    $otherUserInfo = Client::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    // profile_image
                    $otherUserInfo->profile_image = asset('images/users/' . $otherUserInfo->profile_image);
                } else {
                    $otherUserInfo = Helper::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    // profile_image
                    $otherUserInfo->profile_image = asset('images/users/' . $otherUserInfo->profile_image);
                }
            }
        }

        // Return a json object
        // return response()->json(['success' => true, 'otherUserInfo' => $otherUserInfo, 'data' => $messages]);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Chats retrieved successfully',
            'data' => [
                'otherUserInfo' => $otherUserInfo,
                'data' => $messages
            ]
        ], 200);
    }

    public function sendMessage(Request $request)
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

        // Retrieve the user
        $user = User::findOrFail(Auth::user()->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'chat_id' => 'required',
            'message' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $chat = Chat::find($request->chat_id);

        if (!$chat) {
            // return response()->json(['success' => false, 'data' => 'Unable to find chat']);
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unable to find chat .',
                'errors' => 'Unable to find chat',
            ], 401);
        }

        if ($chat->user1_id != Auth::user()->id && $chat->user2_id != Auth::user()->id) {
            // return response()->json(['success' => false, 'data' => 'Unable to send message']);
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unable to send message .',
                'errors' => 'Unable to send message',
            ], 401);
        }

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => Auth::user()->id,
            'message' => $request->message,
        ]);

        // Call notificaion client to send notification
        app('notificationHelper')->sendNotification(Auth::user()->id, $chat->user1_id == Auth::user()->id ? $chat->user2_id : $chat->user1_id, 'client', 'chat', $request->chat_id, 'New Message', 'New message from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name);


        // Return a json object
        // return response()->json(['success' => true, 'data' => $message]);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Message sent successfully',
            'data' => $message
        ], 200);
    }



    // adminChat
    public function adminChat()
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

        // Check chat between user and admin already exists
        $chat = Chat::where('user1_id', 1)->where('user2_id', Auth::user()->id)->orWhere('user1_id', Auth::user()->id)->where('user2_id', 1)->first();
        if (!$chat) {
            // Createa
            $chat = new Chat();
            $chat->user1_id = 1;
            $chat->user2_id = Auth::user()->id;
            $chat->save();
        }

        // return redirect('/client/chats/' . $chat->id);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Admin chat retrieved successfully',
            'data' => $chat->id
        ], 200);
    }


    // deleteAccount
    public function deleteAccount(): JsonResponse
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

        // Validate password & reason

        $validator = Validator::make(request()->all(), [
            'password' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Retrieve the user
        $user = User::findOrFail(Auth::user()->id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Check and matach password

        if (!Hash::check(request('password'), $user->password)) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // updatea is_deleted to 1
        $user->is_deleted = 1;
        $user->deleted_at = now();
        $user->save();


        // Return a json object
        // return response()->json(['success' => true, 'data' => $user]);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Account deleted successfully',
            'data' => $user
        ], 200);
    }
}
