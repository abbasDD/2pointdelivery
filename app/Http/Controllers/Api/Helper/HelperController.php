<?php

namespace App\Http\Controllers\Api\Helper;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\SocialLink;
use App\Models\User;
use App\Models\UserNotification;
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

        $data['bookings'] = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'booking_date', 'booking_time', 'status', 'total_price')
            ->whereIn('status', ['pending'])
            ->get();


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
        ];

        $client = Client::where('user_id', $user->id)->first();
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

        $helper = Helper::where('user_id', $user->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = $user->id;
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
            'tax_id' => $helper->tax_id
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


        $helper = Helper::where('user_id', $user->id)->first();
        if (!$helper) {
            // Create a new helper
            $helper = new Helper();
            $helper->user_id = $user->id;
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
}
