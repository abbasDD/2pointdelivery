<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class PassportAuthController extends Controller
{

    /**
     * User registration
     */
    public function register(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'user_type' => 'required|in:client,helper',
            'account_type' => 'required|in:individual,company',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $userData = $request->only('email', 'password', 'user_type', 'account_type');

        // Check if user already exists
        if (User::where('email', $userData['email'])->exists()) {
            return response()->json([
                'success' => false,
                'statusCode' => 409,
                'message' => 'User already exists.',
            ], 409);
        }

        $userData['email_verified_at'] = now();

        // Check if user_type is helper
        if ($userData['user_type'] == 'helper') {
            $userData['helper_enabled'] = 1;
        } else {
            // Check if user_type is client
            $userData['client_enabled'] = 1;
        }

        // Change user_type to user 
        $userData['user_type'] = 'user';

        // Generate referral code
        $referralCode = strtoupper(Str::random(8));

        // Generate uuid and ensure it is unique
        do {
            $referralCode = strtoupper(Str::random(8));
            $codeExist = User::where('referral_code', $referralCode)->first();
        } while ($codeExist);

        $userData['referral_code'] = $referralCode;


        $user = User::create($userData);
        $tokenResult = $user->createToken('2PointDeliveryJWTAuthenticationToken');
        $accessToken = $tokenResult->accessToken;

        // Create a client is user_type is client
        if ($user['client_enabled'] == 1) {
            $client = new Client();
            $client->user_id = $user->id;
            if ($userData['account_type'] == 'company') {
                $client->company_enabled = 1;
            }
            $client->save();
        }

        // Create a helper if user_type is helper
        if ($user['helper_enabled'] == 1) {
            $helper = new Helper();
            $helper->user_id = $user->id;
            if ($userData['account_type'] == 'company') {
                $helper->company_enabled = 1;
            }
            $helper->save();
        }

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
        ];


        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'User has been registered successfully.',
            'accessToken' => $accessToken,
            'data' => $userData,
        ], 200);
    }

    /**
     * Login User
     */
    public function login(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
            'password' => 'required|string',
            'user_type' => 'required|in:client,helper',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        // Check if user exists
        if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
            $user = Auth::user();

            // Check is user_type is user

            if ($user->user_type != 'user') {

                $user->tokens()->delete();

                return response()->json([
                    'success' => false,
                    'statusCode' => 401,
                    'message' => 'Unauthorized.',
                    'errors' => 'Unauthorized',
                ], 401);
            }

            // Check if user is active

            if ($user->is_active == 0) {

                $user->tokens()->delete();

                return response()->json([
                    'success' => false,
                    'statusCode' => 401,
                    'message' => 'Your account is not active.',
                    'errors' => 'Inactive',
                ], 401);
            }


            $user = $request->user();
            $tokenResult = $user->createToken('2PointDeliveryJWTAuthenticationToken');
            $accessToken = $tokenResult->accessToken;

            // remove extra field from user

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
            ];

            // If user is client
            if ($request->user_type == 'client') {

                $client = Client::where('user_id', $user->id)->first();
                $userData['company_enabled'] = $client->company_enabled;
                $userData['first_name'] = $client->first_name;
                $userData['middle_name'] = $client->middle_name;
                $userData['last_name'] = $client->last_name;
                $userData['profile_image'] = $client->profile_image == null ? asset('images/users/default.png') : asset('images/users/' . $client->profile_image);
            }

            // If user is helper

            if ($request->user_type == 'helper') {

                $helper = Helper::where('user_id', $user->id)->first();
                $userData['company_enabled'] = $helper->company_enabled;
                $userData['first_name'] = $helper->first_name;
                $userData['middle_name'] = $helper->middle_name;
                $userData['last_name'] = $helper->last_name;
                $userData['profile_image'] = $helper->profile_image == null ? asset('images/users/default.png') : asset('images/users/' . $helper->profile_image);
            }

            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'message' => 'User has been logged successfully.',
                'accessToken' => $accessToken,
                'data' => $userData,
            ], 200);

            // return $this->respondWithToken($token);
        } else {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }
    }

    /**
     * Login user
     *
     * @param  LoginRequest  $request
     */
    public function me(): JsonResponse
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

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Authenticated use info.',
            'data' => $user,
        ], 200);
    }


    /**
     * Logout
     */
    public function logout(): JsonResponse
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

        Auth::user()->tokens()->delete();

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'Logged out successfully.',
        ], 200);
    }
}
