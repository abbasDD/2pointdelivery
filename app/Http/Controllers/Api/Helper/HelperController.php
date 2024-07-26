<?php

namespace App\Http\Controllers\Api\Helper;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\SocialLink;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HelperController extends Controller
{

    // home
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

        $user = auth()->user();

        // Calculate helper earnings
        $helper_earnings = Booking::where('bookings.helper_user_id', auth()->user()->id)
            ->join('booking_deliveries', 'bookings.id', '=', 'booking_deliveries.booking_id')
            ->where('bookings.status', 'completed')
            ->sum('booking_deliveries.helper_fee');

        // Statistics
        $data = [
            'total_bookings' => Booking::where('helper_user_id', auth()->user()->id)->count(),
            'accepted_bookings' => Booking::where('helper_user_id', auth()->user()->id)->where('status', 'accepted')->count(),
            'cancelled_bookings' => Booking::where('helper_user_id', auth()->user()->id)->where('status', 'cancelled')->count(),
            'total_earnings' => $helper_earnings,
        ];

        $helper = Helper::where('user_id', auth()->user()->id)->first();

        // Get helperServices list
        $helperServices = $helper->service_types();

        // pluck the service type ids
        $helperServiceIds = $helperServices->pluck('id')->toArray();
        // dd($helperServiceIds);

        $data['bookings'] = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'booking_date', 'booking_time', 'status', 'total_price')
            ->where('status', 'pending')
            ->where('client_user_id', '!=', auth()->user()->id)
            ->whereIn('service_type_id', $helperServiceIds)
            ->orderBy('bookings.updated_at', 'desc')
            ->get();

        $data['personal_details'] = false;
        $data['address_details'] = false;
        $data['company_details'] = false;
        $data['vehicle_details'] = false;
        $data['is_approved'] = false;

        // Get helper details
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        // Check if helper completed its personal details
        if (isset($helper) && $helper->first_name != null) {
            $data['personal_details'] = true;
        }

        // Check if helper completed its address details
        if (isset($helper) && $helper->zip_code != null) {
            $data['address_details'] = true;
        }

        if ($helper->company_enabled == 1) {
            // Get helper company details
            $helperCompany = ClientCompany::where('user_id', auth()->user()->id)->first();
            if (isset($helperCompany) && $helperCompany->legal_name != null) {
                $data['company_details'] = true;
            }
        }

        // Check if helpervehicle details exist
        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if (isset($helperVehicle) && $helperVehicle->vehicle_number != null) {
            $data['vehicle_details'] = true;
        }

        // if helper is approved
        if ($helper->is_approved == 1) {
            $data['is_approved'] = true;
        }


        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'data' => $data,
        ], 200);
    }

    // switchToClient
    public function switchToClient(): JsonResponse
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
            'is_approved' => 0
        ];

        // Get Client data from DB
        $client = Client::where('user_id', auth()->user()->id)->first();

        // If client not found
        if (!$client) {
            // Check if Helper is created with same id
            $helper = Helper::where('user_id', auth()->user()->id)->first();

            // If helper is found then duplicate data to client
            if ($helper) {
                // Check if helper first name and last name is not null
                if ($helper->first_name == null || $helper->last_name == null) {
                    return redirect()->route('helper.profile')->with('error', 'Please fill your helper detail first');
                }

                $client = Client::create([
                    'user_id' => auth()->user()->id,
                    'company_enabled' => $helper->company_enabled ?? 0,
                    'first_name' => $helper->first_name ?? '',
                    'middle_name' => $helper->middle_name ?? '',
                    'last_name' => $helper->last_name ?? '',
                    'gender' => $helper->gender ?? '',
                    'date_of_birth' => $helper->date_of_birth ?? '',
                    'tax_id' => $helper->tax_id ?? '',
                    'phone_no' => $helper->phone_no ?? '',
                    'suite' => $helper->suite ?? '',
                    'street' => $helper->street ?? '',
                    'city' => $helper->city     ?? '',
                    'state' => $helper->state ?? '',
                    'country' => $helper->country ?? '',
                    'zip_code' => $helper->zip_code ?? '',
                ]);
            }
            // If not then create a simple client
            else {
                $client = Client::create([
                    'user_id' => auth()->user()->id,
                ]);
            }
        }

        $client = Client::where('user_id', auth()->user()->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = auth()->user()->id;
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
            $clientCompany = ClientCompany::where('user_id', auth()->user()->id)->first();
            if (isset($clientCompany) && $clientCompany->legal_name != null) {
                $responseData['company_details'] = true;
            }
        }

        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Switched to client account successfully',
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

        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
        }

        $helperData = [
            'account_type' => $helper->company_enabled ? 'company' : 'individual',
            'first_name' => $helper->first_name,
            'middle_name' => $helper->middle_name,
            'last_name' => $helper->last_name,
            'phone_no' => $helper->phone_no,
            'gender' => $helper->gender,
            'date_of_birth' => $helper->date_of_birth,
            'email' => $user->email,
            'profile_image' => $helper->profile_image ? asset('images/users/' . $helper->profile_image) : asset('images/users/default.png'),
            'service_badge_id' => $helper->service_badge_id
        ];


        return response()->json([
            'success' => true,
            'message' => 'Helper Profile fetched successfully',
            'data' => $helperData
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

        // If helper is found, update its attributes
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
        }

        // Set default profile image to null
        $profile_image = $helper->profile_image ?? null;

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
            'profile_image' => $profile_image
        ];

        // If middle_name is not null

        if ($request->has('middle_name')) {
            $updated_data['middle_name'] = $request->middle_name;
        }

        // If service_badge_id is not null

        if ($request->has('service_badge_id')) {
            $updated_data['service_badge_id'] = $request->service_badge_id;
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

        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
        }

        // Get helper company
        $helperCompany = HelperCompany::where('user_id', auth()->user()->id)->first();

        if (!$helperCompany) {
            // Create
            $helperCompany = new HelperCompany();
            $helperCompany->user_id = auth()->user()->id;
            $helperCompany->helper_id = $helper->id;
            $helperCompany->save();
        }

        $helperCompanyData = [
            'company_logo' => $helperCompany->company_logo ? asset('images/company/' . $helperCompany->company_logo) : asset('images/users/default.png'),
            'company_alias' => $helperCompany->company_alias,
            'legal_name' => $helperCompany->legal_name,
            'industry' => $helperCompany->industry,
            'company_number' => $helperCompany->company_number,
            'gst_number' => $helperCompany->gst_number,
            'website_url' => $helperCompany->website_url,
            'email' => $helperCompany->email,
            'business_phone' => $helperCompany->business_phone,
            'suite' => $helperCompany->suite,
            'street' => $helperCompany->street,
            'city' => $helperCompany->city,
            'state' => $helperCompany->state,
            'country' => $helperCompany->country,
            'zip_code' => $helperCompany->zip_code
        ];


        return response()->json([
            'success' => true,
            'message' => 'Client Company Profile fetched successfully',
            'data' => $helperCompanyData
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


        // If helper is found, update its attributes
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
        }

        // Get helper company
        $helperCompany = HelperCompany::where('user_id', auth()->user()->id)->first();

        if (!$helperCompany) {
            // Create
            $helperCompany = new HelperCompany();
            $helperCompany->user_id = auth()->user()->id;
            $helperCompany->helper_id = $helper->id;
            $helperCompany->save();
        }

        $company_logo = $helperCompany->company_logo ?? null;

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



        // Update helperCompany
        $helperCompany->update($updated_data);


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

        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
        }


        $helperData = [
            'suite' => $helper->suite,
            'street' => $helper->street,
            'city' => $helper->city,
            'state' => $helper->state,
            'country' => $helper->country,
            'zip_code' => $helper->zip_code
        ];

        return response()->json([
            'success' => true,
            'message' => 'Address fetched successfully',
            'data' => $helperData
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

        // If helper is found, update its attributes
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
        }


        $updated_data = [
            'suite' => $request->suite,
            'street' => $request->street,
            'city' => $request->city,
            'state' => $request->state,
            'country' => $request->country,
            'zip_code' => $request->zip_code
        ];


        $helper->update($updated_data);

        return response()->json([
            'success' => true,
            'message' => 'Address updated successfully',
            'data' => []
        ], 200);
    }

    // getVehicleInfo
    public function getVehicleInfo(): JsonResponse
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

        $helperVehicleData = [
            'vehicle_type_id' => 0,
            'vehicle_number' => '',
            'vehicle_make' => '',
            'vehicle_model' => '',
            'vehicle_color' => '',
            'vehicle_year' => ''
        ];

        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if ($helperVehicle) {
            // Create a new helper vehicle
            $helperVehicleData = [
                'vehicle_type_id' => $helperVehicle->vehicle_type_id,
                'vehicle_number' => $helperVehicle->vehicle_number,
                'vehicle_make' => $helperVehicle->vehicle_make,
                'vehicle_model' => $helperVehicle->vehicle_model,
                'vehicle_color' => $helperVehicle->vehicle_color,
                'vehicle_year' => $helperVehicle->vehicle_year
            ];
        }


        // Vehicle Types

        $vehicleTypes = VehicleType::select('id', 'uuid', 'name')->where('is_active', 1)->get();

        return response()->json([
            'success' => true,
            'message' => 'Helper Vehicle data fetched successfully',
            'data' => ['helperVehicleData' => $helperVehicleData, 'vehicleTypes' => $vehicleTypes]
        ], 200);
    }


    // vehicleInfoUpdate
    public function vehicleInfoUpdate(Request $request): JsonResponse
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
            // 'vehicle_type_id ' => 'required',
            'vehicle_number' => 'required|string',
            'vehicle_make' => 'required|string',
            'vehicle_model' => 'required|string',
            'vehicle_color' => 'required|string',
            'vehicle_year' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // If helperVehicle is found, update its attributes
        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if (!$helperVehicle) {
            // Create a new helperVehicle
            $helperVehicle = new HelperVehicle();
            $helperVehicle->user_id = auth()->user()->id;
            $helperVehicle->helper_id = Helper::where('user_id', auth()->user()->id)->first()->id;
            $helperVehicle->vehicle_type_id = $request->vehicle_type_id;
            $helperVehicle->save();
        }


        $updated_data = [
            'vehicle_type_id' => $request->vehicle_type_id,
            'vehicle_number' => $request->vehicle_number,
            'vehicle_make' => $request->vehicle_make,
            'vehicle_model' => $request->vehicle_model,
            'vehicle_color' => $request->vehicle_color,
            'vehicle_year' => $request->vehicle_year
        ];


        $helperVehicle->update($updated_data);

        return response()->json([
            'success' => true,
            'message' => 'Vehicle info updated successfully',
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


        // Get the user and update its password
        $user = User::find(auth()->user()->id);

        // Check if old password is correct
        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Old password is incorrect',
                'errors' => []
            ], 422);
        }

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
            $facebookLink->link = $request->facebook ?? $facebookLink->link;
            $facebookLink->save();
        } else {
            $facebookLink = new SocialLink();
            $facebookLink->user_id = auth()->user()->id;
            $facebookLink->user_type = 'helper';
            $facebookLink->key = 'facebook';
            $facebookLink->link = $request->facebook ?? $facebookLink->link;
            $facebookLink->save();
        }

        // Get linkedin
        $linkedinLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'linkedin')->first();
        if ($linkedinLink) {
            $linkedinLink->link = $request->linkedin ?? $linkedinLink->link;
            $linkedinLink->save();
        } else {
            $linkedinLink = new SocialLink();
            $linkedinLink->user_id = auth()->user()->id;
            $linkedinLink->user_type = 'helper';
            $linkedinLink->key = 'linkedin';
            $linkedinLink->link = $request->linkedin ?? $linkedinLink->link;
            $linkedinLink->save();
        }

        // Get instagram
        $instagramLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'instagram')->first();
        if ($instagramLink) {
            $instagramLink->link = $request->instagram ?? $instagramLink->link;
            $instagramLink->save();
        } else {
            $instagramLink = new SocialLink();
            $instagramLink->user_id = auth()->user()->id;
            $instagramLink->user_type = 'helper';
            $instagramLink->key = 'instagram';
            $instagramLink->link = $request->instagram ?? $instagramLink->link;
            $instagramLink->save();
        }


        // Get tiktok
        $tiktokLink = SocialLink::where('user_id', auth()->user()->id)->where('key', 'tiktok')->first();
        if ($tiktokLink) {
            $tiktokLink->link = $request->tiktok ?? $tiktokLink->link;
            $tiktokLink->save();
        } else {
            $tiktokLink = new SocialLink();
            $tiktokLink->user_id = auth()->user()->id;
            $tiktokLink->user_type = 'helper';
            $tiktokLink->key = 'tiktok';
            $tiktokLink->link = $request->tiktok ?? $tiktokLink->link;
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
            ->where('receiver_user_type', 'helper')
            ->orderBy('created_at', 'asc')->take(10)->get();
        $unread_notification = UserNotification::where('receiver_user_id', auth()->user()->id)
            ->where('receiver_user_type', 'helper')
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
        UserNotification::where('receiver_user_id', auth()->user()->id)->where('receiver_user_type', 'helper')->update(['read' => 1]);

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

        $acceptedInvites = TeamInvitation::where('inviter_id', auth()->user()->id)->get();
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
            ->where('invitee_id', auth()->user()->id)
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
