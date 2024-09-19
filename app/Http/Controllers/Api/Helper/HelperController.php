<?php

namespace App\Http\Controllers\Api\Helper;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Chat;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperBankAccount;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\KycDetail;
use App\Models\Message;
use App\Models\ServiceType;
use App\Models\SocialLink;
use App\Models\TeamInvitation;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserSwitch;
use App\Models\UserWallet;
use App\Models\VehicleType;
use App\Models\WithdrawRequest;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

class HelperController extends Controller
{

    // Get Helper Profile
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
            'is_approved' => null,
            'is_notified' => 0
        ];


        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
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

        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Helper profile account fetched successfully',
            'data' => $userData,
        ], 200);
    }

    // home
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

        $user = User::find(Auth::user()->id);

        // Calculate helper delivery earnings
        $helper_earnings = Booking::where('bookings.helper_user_id', Auth::user()->id)
            ->join('booking_deliveries', 'bookings.id', '=', 'booking_deliveries.booking_id')
            ->where('bookings.status', 'completed')
            ->sum('booking_deliveries.helper_fee');

        $userId = Auth::user()->id;

        // Calculate helper moving earnings
        $helper_earnings += Booking::where('bookings.status', 'completed')
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->join('booking_movings', 'bookings.id', '=', 'booking_movings.booking_id')
            ->sum('booking_movings.helper_fee');

        // Statistics
        $data = [
            'total_bookings' => Booking::where('helper_user_id', Auth::user()->id)->count(),
            'accepted_bookings' => Booking::where('helper_user_id', Auth::user()->id)->where('status', 'accepted')->count(),
            'cancelled_bookings' => Booking::where('helper_user_id', Auth::user()->id)->where('status', 'cancelled')->count(),
            'total_earnings' => $helper_earnings,
        ];

        $helper = Helper::where('user_id', Auth::user()->id)->first();

        // Get helperServices list
        $helperServices = $helper->service_types();

        // pluck the service type ids
        $helperServiceIds = $helperServices->pluck('id')->toArray();
        // dd($helperServiceIds);

        $data['bookings'] = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'booking_date', 'booking_time', 'status')
            ->where('status', 'pending')
            ->where('client_user_id', '!=', Auth::user()->id)
            ->whereIn('service_type_id', $helperServiceIds)
            ->orderBy('bookings.updated_at', 'desc')
            ->get();

        $data['personal_details'] = false;
        $data['address_details'] = false;
        $data['company_details'] = false;
        $data['vehicle_details'] = false;
        $data['is_approved'] = false;
        $data['is_kyc_approved'] = false;
        $data['is_vehicle_approved'] = false;

        // Get helper details
        $helper = Helper::where('user_id', Auth::user()->id)->first();
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
            $helperCompany = HelperCompany::where('user_id', Auth::user()->id)->first();
            if (isset($helperCompany) && $helperCompany->legal_name != null) {
                $data['company_details'] = true;
            }
        }

        // Check if helpervehicle details exist
        $helperVehicle = HelperVehicle::where('user_id', Auth::user()->id)->first();
        if (isset($helperVehicle) && $helperVehicle->vehicle_number != null) {
            $data['vehicle_details'] = true;

            // Check if vehicle approved
            if ($helperVehicle->is_approved == 1) {
                $data['is_vehicle_approved'] = true;
            }
        }

        // if helper is approved
        if ($helper->is_approved == 1) {
            $data['is_approved'] = true;
        }

        // Check kyc added and approved
        $kycDetail = KycDetail::where('user_id', Auth::user()->id)->where('is_verified', 1)->first();
        if ($kycDetail) {
            $data['is_kyc_approved'] = true;
        }

        // Unread notification count for user
        $data['unreadNotificationCount'] = UserNotification::where('receiver_user_id', Auth::user()->id)->where('receiver_user_type', 'helper')->where('read', 0)->count() ?? 0;


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
            'is_approved' => 0,
            'is_notified' => 0
        ];

        // Get Client data from DB
        $client = Client::where('user_id', Auth::user()->id)->first();

        // If client not found
        if (!$client) {
            // Check if Helper is created with same id
            $helper = Helper::where('user_id', Auth::user()->id)->first();

            // If helper is found then duplicate data to client
            if ($helper) {
                // Check if helper first name and last name is not null
                if ($helper->first_name == null || $helper->last_name == null) {
                    // return redirect()->route('helper.profile')->with('error', 'Please fill your helper detail first');
                    return response()->json([
                        'success' => false,
                        'statusCode' => 422,
                        'message' => 'Please fill your helper detail first',
                        'errors' => 'Please fill your helper detail first',
                    ], 422);
                }

                $client = Client::create([
                    'user_id' => Auth::user()->id,
                    'company_enabled' => $helper->company_enabled ?? 0,
                    'first_name' => $helper->first_name ?? '',
                    'middle_name' => $helper->middle_name ?? '',
                    'last_name' => $helper->last_name ?? '',
                    'gender' => $helper->gender ?? '',
                    'date_of_birth' => $helper->date_of_birth ?? '',
                    'profile_image' => $helper->profile_image ?? '',
                    'thumbnail' => $helper->thumbnail ?? '',
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
                    'user_id' => Auth::user()->id,
                ]);
            }
        }

        $client = Client::where('user_id', Auth::user()->id)->first();
        if (!$client) {
            // Create a new client
            $client = new Client();
            $client->user_id = Auth::user()->id;
            $client->save();
        }
        $userData['company_enabled'] = $client->company_enabled;
        $userData['first_name'] = $client->first_name;
        $userData['middle_name'] = $client->middle_name;
        $userData['last_name'] = $client->last_name;
        $userData['profile_image'] = $client->thumbnail == null ? asset('images/users/default.png') : asset('images/users/thumbnail/' . $client->thumbnail);
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
            $clientCompany = ClientCompany::where('user_id', Auth::user()->id)->first();
            if (isset($clientCompany) && $clientCompany->legal_name != null) {
                $userData['company_details'] = true;
            }
        }

        // Make client_enabled = 1
        $user->client_enabled = 1;
        $user->save();

        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Switched to client account successfully',
            'data' => $userData,
        ], 200);
    }

    // toggleNotification
    public function toggleNotification(): JsonResponse
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

        // Get helper
        $helper = Helper::where('user_id', Auth::user()->id)->first();


        if (!$helper) {
            // Response not found
            return response()->json([
                'success' => false,
                'statusCode' => 404,
                'message' => 'Helper not found.',
                'errors' => 'Helper not found',
            ], 404);
        }

        // Toggle is_notified field
        $helper->is_notified = $helper->is_notified == 1 ? 0 : 1;
        $helper->save();


        // Success response
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Notification toggled successfully',
            'data' => $helper->is_notified
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

        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
            $helper->save();
        }

        // Get helperServices list
        $helperServices = $helper->service_types();

        // pluck the service type ids
        $helperServiceIds = $helperServices->pluck('id')->toArray();

        $helperData = [
            'account_type' => $helper->company_enabled ? 'company' : 'individual',
            'first_name' => $helper->first_name,
            'middle_name' => $helper->middle_name,
            'last_name' => $helper->last_name,
            'phone_no' => $helper->phone_no,
            'gender' => $helper->gender,
            'date_of_birth' => $helper->date_of_birth,
            'is_approved' => $helper->is_approved ?? 0,
            'email' => $user->email,
            'profile_image' => $helper->thumbnail ? asset('images/users/thumbnail/' . $helper->thumbnail) : asset('images/users/default.png'),
            'service_badge_id' => $helper->service_badge_id,
            'helperServiceIds' => $helperServiceIds
        ];


        return response()->json([
            'success' => true,
            'message' => 'Helper Personal Profile fetched successfully',
            'data' => [
                'helperData' => $helperData,
                'services' => ServiceType::where('is_active', 1)->get(),
            ],
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
            'gender' => 'required|in:male,female',
            'date_of_birth' => 'required|string',
            // array of services ids
            'services' => 'required|array',

        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }


        // If helper is found, update its attributes
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
            $helper->save();
        }

        // Set default profile image to null
        $profile_image = $helper->profile_image ?? null;
        $thumbnail = $helper->thumbnail ?? null;

        // Upload the profile image if provided
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
            'profile_image' => $profile_image,
            'thumbnail' => $thumbnail
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

        // Sync services for the vehicle type
        $helper->service_types()->sync($request->services);


        return response()->json([
            'success' => true,
            'message' => 'Helper Profile updated successfully',
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

        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
            $helper->save();
        }

        // Get helper company
        $helperCompany = HelperCompany::where('user_id', Auth::user()->id)->first();

        if (!$helperCompany) {
            // Create
            $helperCompany = new HelperCompany();
            $helperCompany->user_id = Auth::user()->id;
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


        // If helper is found, update its attributes
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
            $helper->save();
        }

        // Get helper company
        $helperCompany = HelperCompany::where('user_id', Auth::user()->id)->first();

        if (!$helperCompany) {
            // Create
            $helperCompany = new HelperCompany();
            $helperCompany->user_id = Auth::user()->id;
            $helperCompany->helper_id = $helper->id;
            $helperCompany->save();
        }

        $company_logo = $helperCompany->company_logo ?? null;
        $thumbnail = $helperCompany->thumbnail ?? null;

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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
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

        // If helper is found, update its attributes
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
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

        if (!Auth::user()) {
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
            'vehicle_year' => '',
            'vehicle_image' => asset('images/default.png'),
            'is_approved' => null
        ];

        $helperVehicle = HelperVehicle::where('user_id', Auth::user()->id)->first();
        if ($helperVehicle) {
            // Create a new helper vehicle
            $helperVehicleData = [
                'vehicle_type_id' => $helperVehicle->vehicle_type_id,
                'vehicle_number' => $helperVehicle->vehicle_number,
                'vehicle_make' => $helperVehicle->vehicle_make,
                'vehicle_model' => $helperVehicle->vehicle_model,
                'vehicle_color' => $helperVehicle->vehicle_color,
                'vehicle_year' => $helperVehicle->vehicle_year,
                'vehicle_image' => $helperVehicle->vehicle_image ? asset('images/helper_vehicles/' . $helperVehicle->vehicle_image) : asset('images/default.png'),
                'is_approved' => $helperVehicle->is_approved
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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $validator = Validator::make($request->all(), [
            'vehicle_type_id' => 'required',
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

        // Check if vehicle type exist
        $vehicleType = VehicleType::where('id', $request->vehicle_type_id)->first();
        if (!$vehicleType) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle type not found',
                'errors' => 'Vehicle type not found'
            ], 404);
        }

        // If helperVehicle is found, update its attributes
        $helperVehicle = HelperVehicle::where('user_id', Auth::user()->id)->first();
        if (!$helperVehicle) {
            // Create a new helperVehicle
            $helperVehicle = new HelperVehicle();
            $helperVehicle->user_id = Auth::user()->id;
            $helperVehicle->helper_id = Helper::where('user_id', Auth::user()->id)->first()->id;
            $helperVehicle->vehicle_type_id = $request->vehicle_type_id;
            $helperVehicle->save();
        }

        // Set default profile image to null
        $vehicle_image = $helperVehicle->vehicle_image ?? null;
        $thumbnail = $helperVehicle->thumbnail ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('vehicle_image')) {
            $image = Image::read($request->file('vehicle_image'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('vehicle_image')->getClientOriginalExtension();
            $destinationPath = public_path('images/helper_vehicles/');
            $image->save($destinationPath . $imageName);

            // Generate Thumbnail Image Upload on Folder Code
            $destinationPathThumbnail = public_path('images/helper_vehicles/thumbnail/');
            $image->resize(100, 100);
            $image->save($destinationPathThumbnail . $imageName);

            $vehicle_image = $imageName;
            $thumbnail = $imageName;
        }


        $updated_data = [
            'vehicle_type_id' => $request->vehicle_type_id,
            'vehicle_number' => $request->vehicle_number,
            'vehicle_make' => $request->vehicle_make,
            'vehicle_model' => $request->vehicle_model,
            'vehicle_color' => $request->vehicle_color,
            'vehicle_year' => $request->vehicle_year,
            'vehicle_image' => $vehicle_image,
            'thumbnail' => $thumbnail
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


        // Get the user and update its password
        $user = User::find(Auth::user()->id);

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
            $facebookLink->link = $request->facebook ?? $facebookLink->link;
            $facebookLink->save();
        } else {
            $facebookLink = new SocialLink();
            $facebookLink->user_id = Auth::user()->id;
            $facebookLink->user_type = 'helper';
            $facebookLink->key = 'facebook';
            $facebookLink->link = $request->facebook ?? $facebookLink->link;
            $facebookLink->save();
        }

        // Get linkedin
        $linkedinLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'linkedin')->first();
        if ($linkedinLink) {
            $linkedinLink->link = $request->linkedin ?? $linkedinLink->link;
            $linkedinLink->save();
        } else {
            $linkedinLink = new SocialLink();
            $linkedinLink->user_id = Auth::user()->id;
            $linkedinLink->user_type = 'helper';
            $linkedinLink->key = 'linkedin';
            $linkedinLink->link = $request->linkedin ?? $linkedinLink->link;
            $linkedinLink->save();
        }

        // Get instagram
        $instagramLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'instagram')->first();
        if ($instagramLink) {
            $instagramLink->link = $request->instagram ?? $instagramLink->link;
            $instagramLink->save();
        } else {
            $instagramLink = new SocialLink();
            $instagramLink->user_id = Auth::user()->id;
            $instagramLink->user_type = 'helper';
            $instagramLink->key = 'instagram';
            $instagramLink->link = $request->instagram ?? $instagramLink->link;
            $instagramLink->save();
        }


        // Get tiktok
        $tiktokLink = SocialLink::where('user_id', Auth::user()->id)->where('key', 'tiktok')->first();
        if ($tiktokLink) {
            $tiktokLink->link = $request->tiktok ?? $tiktokLink->link;
            $tiktokLink->save();
        } else {
            $tiktokLink = new SocialLink();
            $tiktokLink->user_id = Auth::user()->id;
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
            ->where('receiver_user_type', 'helper')
            ->orderBy('created_at', 'asc')->take(10)->get();
        $unread_notification = UserNotification::where('receiver_user_id', Auth::user()->id)
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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Mark all notifications as read
        UserNotification::where('receiver_user_id', Auth::user()->id)->where('receiver_user_type', 'helper')->update(['read' => 1]);

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
            ->where('receiver_user_type', 'helper')
            ->update(['read' => 1]);

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

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        $user = User::find(Auth::user()->id);

        $acceptedInvites = TeamInvitation::where('inviter_id', Auth::user()->id)->get();
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
            ->where('invitee_id', Auth::user()->id)
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
                    $chat->otherUserInfo = Admin::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                } else {

                    if ($otherUser->client_enabled) {
                        $chat->otherUserInfo = Client::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        // profile_image
                        $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    } else {
                        $chat->otherUserInfo = Helper::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    }
                }
            } else {
                $otherUser = User::findOrFail($chat->user1_id);
                // Check if user is admin
                if ($otherUser->user_type == 'admin') {
                    $chat->otherUserInfo = Admin::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                    $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                } else {

                    if ($otherUser->client_enabled) {
                        $chat->otherUserInfo = Client::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
                    } else {
                        $chat->otherUserInfo = Helper::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $otherUser->id)->first();
                        $chat->otherUserInfo->profile_image = $chat->otherUserInfo->thumbnail ? asset('images/users/thumbnail/' . $chat->otherUserInfo->thumbnail) : asset('images/users/default.png');
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
            $userInfo = Admin::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $user->id)->first();
            // profile_image
            $userInfo->profile_image = $userInfo->thumbnail ? asset('images/users/thumbnail/' . $userInfo->thumbnail) : asset('images/users/default.png');
        } else {
            // Get User detail as per user type
            if ($user->client_enabled == 1) {
                $userInfo = Client::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $user->id)->first();
                // profile_image
                $userInfo->profile_image = $userInfo->thumbnail ? asset('images/users/thumbnail/' . $userInfo->thumbnail) : asset('images/users/default.png');
            }

            if ($user->helper_enabled == 1) {
                $userInfo = Helper::select('first_name', 'last_name', 'profile_image', 'thumbnail')->where('user_id', $user->id)->first();
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
        app('notificationHelper')->sendNotification(Auth::user()->id, $chat->user1_id == Auth::user()->id ? $chat->user2_id : $chat->user1_id, 'helper', 'chat', $request->chat_id, 'New Message', 'New message from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name);


        // Return a json object
        // return response()->json(['success' => true, 'data' => $message]);
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Message sent successfully',
            'data' => $message
        ], 200);
    }

    // Wallet APIs -------

    // getWalletBalance
    public function getWalletBalance(): JsonResponse
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

        // Check if user is helper

        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'No balance',
                'data' => [
                    'balance' => 0
                ]
            ], 200);
        }


        // Get balance of helper
        $totalBalance = $this->getHelperWalletBalance();

        // Return a json object
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Balance retrieved successfully',
            'data' => [
                'balance' => $totalBalance
            ]
        ], 200);
    }


    // Get credit wallet history
    public function getWalletEarning(): JsonResponse
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

        // Check if user is helper

        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'No history',
                'data' => []
            ], 200);
        }

        $userId = Auth::user()->id;

        // Get all completed bookings for the helper with only necessary columns and eager loading
        $completedBookings = Booking::select('id', 'uuid', 'booking_type')
            ->where('status', 'completed')
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->with([
                'bookingDelivery:id,booking_id,helper_fee',
                'bookingMoving:id,booking_id,helper_fee'
            ])
            ->get();

        // Transform the bookings into the desired structure
        $completedBookingList = $completedBookings->map(function ($booking) {
            if ($booking->booking_type == 'delivery' && $booking->bookingDelivery) {
                return [
                    'booking_id' => $booking->id,
                    'booking_type' => $booking->booking_type,
                    'booking_uuid' => $booking->uuid,
                    'helper_fee' => $booking->bookingDelivery->helper_fee,
                ];
            } elseif ($booking->booking_type != 'delivery' && $booking->bookingMoving) {
                return [
                    'booking_id' => $booking->id,
                    'booking_type' => $booking->booking_type,
                    'booking_uuid' => $booking->uuid,
                    'helper_fee' => $booking->bookingMoving->helper_fee,
                ];
            }
        })->filter()->values()->all(); // Filter out null values and reindex the array


        // Return a json object
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Credit wallet history retrieved successfully',
            'data' => $completedBookingList
        ], 200);
    }

    // getWalletWithdrawRequests
    public function getWalletWithdrawRequests(): JsonResponse
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

        // Get withdraw requests
        $withdrawRequests = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->get();

        // Return a json object
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Withdraw requests retrieved successfully',
            'data' => $withdrawRequests
        ], 200);
    }

    // postWalletWithdrawRequest
    public function postWalletWithdrawRequest(Request $request): JsonResponse
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

        // validation for amount and reason

        $validator = Validator::make(request()->all(), [
            'bank_id' => 'required',
            'amount' => 'required',
            'reason' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // If aithdraw amount is 0  return error

        if ($request->amount == 0) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Amount must be greater than 0',
                'errors' => 'Amount must be greater than 0'
            ], 422);
        }

        // Check if helper exist

        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Helper not found',
                'errors' => 'Helper not found'
            ], 422);
        }

        // Check if helper bank account exist 
        $helperBankAccount = HelperBankAccount::where('user_id', Auth::user()->id)->where('id', $request->bank_id)->first();
        if (!$helperBankAccount) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Helper bank account not found',
                'errors' => 'Helper bank account not found'
            ], 422);
        }

        // Get balance of helper
        $balance = $this->getHelperWalletBalance();

        if ($balance < $request->amount) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Insufficient balance',
                'errors' => 'Insufficient balance'
            ], 422);
        }

        // Add withdraw request
        // $withdrawRequest = WithdrawRequest::create([
        //     'user_id' => Auth::user()->id,
        //     'helper_id' => $helper->id,
        //     'amount' => $request->amount,
        //     'reason' => $request->reason,
        // ]);

        // Check if user wallet request already pending
        $withdrawRequestExist = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'withdraw')->where('status', 'pending')->first();

        if ($withdrawRequestExist) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Withdraw request already pending',
                'errors' => 'Withdraw request already pending'
            ], 422);
        }

        $withdrawRequest = UserWallet::create([
            'user_id' => Auth::user()->id,
            'user_type' => 'helper',
            'type' => 'withdraw',
            'amount' => $request->amount,
            'note' => $request->reason,
            'payment_method' => $helperBankAccount->payment_method,
            'transaction_id' => $helperBankAccount->account_number,
            'status' => 'pending',
        ]);

        if (!$withdrawRequest) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Withdraw request failed',
                'errors' => 'Withdraw request failed'
            ], 422);
        }

        // Return a json object
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Withdraw request created successfully',
            'data' => []
        ], 200);
    }

    // getBankAccounts
    public function getBankAccounts(): JsonResponse
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

        // Get bank accounts
        $bankAccounts = HelperBankAccount::where('user_id', Auth::user()->id)->get();

        // Return a json object
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Bank accounts retrieved successfully',
            'data' => $bankAccounts
        ], 200);
    }

    // Add Bank Account
    public function addBankAccount(Request $request): JsonResponse
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

        // Validate request
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'payment_method' => 'required|in:paypal,stripe,interac',
        ]);


        // Check if user exist
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Helper not found',
                'errors' => 'Helper not found'
            ], 422);
            // return redirect()->back()->with('error', 'Helper not found');
        }


        // Check if bank account already exist
        $bankAccount = HelperBankAccount::where('user_id', Auth::user()->id)->where('payment_method', $request->payment_method)->first();
        if ($bankAccount) {
            return response()->json([
                'success' => false,
                'statusCode' => 422,
                'message' => 'Bank account already exist',
                'errors' => 'Bank account already exist'
            ], 422);
            // return redirect()->back()->with('error', 'Bank account already exist');
        }


        // Save bank account
        $bankAccount = new HelperBankAccount();
        $bankAccount->user_id = Auth::user()->id;
        $bankAccount->helper_id = $helper->id;
        $bankAccount->account_name = $request->account_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->payment_method = $request->payment_method;
        $bankAccount->is_approved = 0;
        $bankAccount->save();


        // Return a json object
        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Bank account added successfully',
            'data' => $bankAccount
        ], 200);

        // return redirect()->back()->with('success', 'Bank account added successfully');
    }

    // getHelperWalletBalance
    private function getHelperWalletBalance()
    {

        $userId = Auth::user()->id;

        // Get all for completed bookings
        $completedBookings = Booking::where('status', 'completed')
            ->where(function ($query) use ($userId) {
                $query->where('helper_user_id', $userId)
                    ->orWhere('helper_user_id2', $userId);
            })
            ->with(['bookingDelivery', 'bookingMoving'])
            ->get();

        // Initialize total earnings
        $totalEarning = 0;

        // Loop through each completed booking
        foreach ($completedBookings as $booking) {
            if ($booking->booking_type == 'delivery' && $booking->bookingDelivery) {
                $totalEarning += $booking->bookingDelivery->helper_fee;
            } elseif ($booking->booking_type != 'delivery' && $booking->bookingMoving) {
                $totalEarning += $booking->bookingMoving->helper_fee;
            }
        }


        // Total Withdraw Amount
        // $totalWithdraw = WithdrawRequest::where('user_id', Auth::user()->id)->whereIn('status', ['pending', 'approved'])->sum('amount');
        $totalWithdraw = UserWallet::where('user_id', Auth::user()->id)->where('type', 'withdraw')->sum('amount');

        // Total Balance
        $totalBalance = $totalEarning - $totalWithdraw;

        return $totalBalance;
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
