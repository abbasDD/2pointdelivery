<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Helper;
use App\Models\SocialLink;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class HelperController extends Controller
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
    function passwordUpdate(Request $request): JsonResponse
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
    function getSocialLinks(): JsonResponse
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
    function socialLinksUpdate(Request $request): JsonResponse
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
}
