<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\PaymentSetting;
use App\Models\Referral;
use App\Models\SmtpSetting;
use App\Models\SocialLoginSetting;
use App\Models\SystemSetting;
use App\Models\User;
use App\Services\FcmService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Password;
use Laravel\Socialite\Facades\Socialite;

class PassportAuthController extends Controller
{

    protected $fcmService;

    public function __construct(FcmService $fcmService)
    {
        $this->fcmService = $fcmService;
    }

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
            'personal_details' => false,
            'address_details' => false,
            'company_details' => false,
            'is_approved' => 0
        ];

        // Update fcm_token if exists
        if ($request->has('fcm_token')) {
            $user->fcm_token = $request->fcm_token;
            $user->save();
        }

        // Check if user added refferal code
        if ($request->has('referral_code')) {
            $refferr_user = User::where('referral_code', $request->referral_code)->first();
            // dd($refferr_user);
            if ($refferr_user) {
                Referral::create([
                    'referrer_id' => $user->id,
                    'referred_user_id' => $refferr_user->id
                ]);
            }
        }


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

        // if user_type is other than client or helper
        if ($request->user_type != 'client' && $request->user_type != 'helper') {
            return response()->json([
                'success' => false,
                'message' => 'User type is invalid.',
            ], 422);
        }

        // Check if email exist

        $user = User::where('email', $request->email)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Check if user is_deleted

        if ($user->is_deleted == 1) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'User is deleted.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // Check if user is active

        if ($user->is_active == 0) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'User is not active.',
                'errors' => 'Unauthorized',
            ], 401);
        }


        // Check 
        if ($request->user_type == 'client' && $user->client_enabled == 0) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'unable to find client.',
                'errors' => 'Unauthorized',
            ]);
        }

        // Check
        if ($request->user_type == 'helper' && $user->helper_enabled == 0) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'unable to find helper.',
                'errors' => 'Unauthorized',
            ]);
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
                'personal_details' => false,
                'address_details' => false,
                'company_details' => false,
                'vehicle_details' => false,
                'is_approved' => 0
            ];

            // Update fcm_token if exists
            if ($request->has('fcm_token')) {
                $user->fcm_token = $request->fcm_token;
                $user->save();
            }

            // If user is client
            if ($request->user_type == 'client') {

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
                    $clientCompany = ClientCompany::where('user_id', auth()->user()->id)->first();
                    if (isset($clientCompany) && $clientCompany->legal_name != null) {
                        $userData['company_details'] = true;
                    }
                }
            }

            // If user is helper

            if ($request->user_type == 'helper') {

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
                        $userData['company_details'] = true;
                    }
                }

                // Check if helpervehicle details exist
                $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
                if (isset($helperVehicle) && $helperVehicle->vehicle_number != null) {
                    $userData['vehicle_details'] = true;
                }
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
                'message' => 'Invalid credentials.',
                'errors' => 'Invalid credentials.',
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

    // Forget Password
    public function forgetPassword(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation error',
                'errors' => $validator->errors()
            ], 422);
        }

        $smtpSettings = SmtpSetting::get();
        if ($smtpSettings->isEmpty()) {
            return $this->sendResetLinkFailedResponse($request, 'smtp.not_configured');
        }

        $smtpSettingEnabled = $smtpSettings->where('key', 'smtp_enabled')->first();
        if ($smtpSettingEnabled->value == 'no') {
            return $this->sendResetLinkFailedResponse($request, 'smtp.disabled');
        }

        // Configure mailer with the SMTP settings
        config([
            'mail.mailers.smtp.transport' => 'smtp',
            'mail.mailers.smtp.host' => $smtpSettings->where('key', 'smtp_host')->first()->value,
            'mail.mailers.smtp.port' => $smtpSettings->where('key', 'smtp_port')->first()->value,
            'mail.mailers.smtp.encryption' => $smtpSettings->where('key', 'smtp_encryption')->first()->value,
            'mail.mailers.smtp.username' => $smtpSettings->where('key', 'smtp_username')->first()->value,
            'mail.mailers.smtp.password' => $smtpSettings->where('key', 'smtp_password')->first()->value,
            'mail.from.address' => $smtpSettings->where('key', 'smtp_from_email')->first()->value,
            'mail.from.name' => $smtpSettings->where('key', 'smtp_from_name')->first()->value,
        ]);

        $response = $this->broker()->sendResetLink(
            $this->credentials($request)
        );

        return $response == Password::RESET_LINK_SENT
            ? $this->sendResetLinkResponse($request, $response)
            : $this->sendResetLinkFailedResponse($request, $response);

        // Response

        // return response()->json([
        //     'success' => true,
        //     'statusCode' => 200,
        //     'message' => 'Password reset link sent on your email id.',
        // ], 200);
    }

    /**
     * Get the needed authentication credentials from the request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    protected function credentials(Request $request)
    {
        return $request->only('email');
    }


    /**
     * Get the broker to be used during password reset.
     *
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker();
    }

    /**
     * Get the response for a successful password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    protected function sendResetLinkResponse(Request $request, $response)
    {
        return $request->wantsJson()
            ? new JsonResponse(['success' => true, 'statusCode' => 200, 'message' => trans($response)], 200)
            : new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => trans($response)], 422);
    }

    /**
     * Get the response for a failed password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  string  $response
     * @return \Illuminate\Http\RedirectResponse
     *
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
        // if ($request->wantsJson()) {
        //     throw ValidationException::withMessages([
        //         'email' => [trans($response)],
        //     ]);
        // }

        return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => trans($response)], 422);
    }


    /**
     * Social Login
     */
    public function redirectToProvider($provider)
    {
        //  Check if provider is google
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $socialLoginSetting = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$socialLoginSetting) {
                return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_secret_id')->first();
            if (!$socialLoginSetting) {
                return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
            if (!$socialLoginSetting) {
                return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
            }

            // Load as config
            config(['services.google.client_id' => $socialLoginSetting->value]);
            config(['services.google.client_secret' => $socialLoginSetting->value]);
            config(['services.google.redirect' => $socialLoginSetting->value]);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {

        //  Check if provider is google
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $socialLoginSetting = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$socialLoginSetting) {
                return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_secret_id')->first();
            if (!$socialLoginSetting) {
                return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
            if (!$socialLoginSetting) {
                return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
            }

            // Load as config
            config(['services.google.client_id' => $socialLoginSetting->value]);
            config(['services.google.client_secret' => $socialLoginSetting->value]);
            config(['services.google.redirect' => $socialLoginSetting->value]);
        }

        $socialUser = Socialite::driver($provider)->stateless()->user();

        // Check if the user exists by provider ID
        $user = User::where('provider_id', $socialUser->getId())->first();

        if (!$user) {
            // Create a new user if not exists
            $user = User::create([
                'name' => $socialUser->getName(),
                'email' => $socialUser->getEmail(), // This might be null
                'provider_name' => $provider,
                'provider_id' => $socialUser->getId(),
                'password' => null, // No password for social logins
            ]);
        }

        // Log the user in
        Auth::login($user);

        // Create a token for the user
        $tokenResult = $user->createToken('2PointDeliveryJWTAuthenticationToken');
        $token = $tokenResult->accessToken;

        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'token' => $token,
            'message' => 'Logged in successfully.',
        ], 200);
    }

    // sendTestNotification

    public function sendTestNotification(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
            'title' => 'required',
            'body' => 'required',
        ]);

        $result = $this->fcmService->sendFcmNotification($request->user_id, $request->title, $request->body);

        if ($result['success']) {
            return response()->json(['message' => $result['message'], 'response' => $result['response']]);
        } else {
            return response()->json(['message' => $result['message']], 500);
        }
    }

    public function googleLogin(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        // Get config from SocialLoginSetting
        $socialLoginSetting = SocialLoginSetting::where('key', 'google_client_id')->first();
        if (!$socialLoginSetting) {
            return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
        }

        $socialLoginSetting = SocialLoginSetting::where('key', 'google_secret_id')->first();
        if (!$socialLoginSetting) {
            return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
        }

        $socialLoginSetting = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
        if (!$socialLoginSetting) {
            return new JsonResponse(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
        }

        // Load as config
        config(['services.google.client_id' => $socialLoginSetting->value]);
        config(['services.google.client_secret' => $socialLoginSetting->value]);
        config(['services.google.redirect' => $socialLoginSetting->value]);

        $googleUser = Socialite::driver('google')->stateless()->userFromToken($request->input('code'));


        // Check if the email already exists in your database
        $user = User::where('email', $googleUser->getEmail())->first();
        if ($user) {
            // If user exists, update the provider information
            $user->update([
                'provider_name' => 'google',
                'provider_id' => $googleUser->getId(),
            ]);
        } else {

            // Create new user
            $user = User::create([
                'email' => $googleUser->getEmail(), // This might be null
                'provider_name' => 'google',
                'provider_id' => $googleUser->getId(),
                'password' => null, // No password for social logins
            ]);

            // Create a Client 
            $client = new Client();
            $client->first_name = $googleUser->getName();
            $client->user_id = $user->id;
            $client->save();
        }

        // Create token
        $tokenResult = $user->createToken('2PointDeliveryJWTAuthenticationToken');
        $token = $tokenResult->accessToken;

        return response()->json(['token' => $token]);
    }

    // appDetails

    public function appDetails(): JsonResponse
    {

        $data = [
            'app_name' => '2 Point Delivery',
            'currency_symbol' => 'cad',
            'language' => 'en',
            'dimension' => 'cm',
            'weight' => 'kg',
            'timezone' => 'UTC',
            'time_format' => 'AM/PM',
            'date_format' => 'DD/MM/YYYY',
        ];

        // Get system settings
        $websiteName = SystemSetting::where('key', 'website_name')->first();
        if ($websiteName) {
            $data['app_name'] = $websiteName->value ?? '2 Point Delivery';
        }

        // Get currency
        $currency = SystemSetting::where('key', 'currency')->first();
        if ($currency) {
            $data['currency_symbol'] = $currency->value ?? 'cad';
        }

        // language
        $language = SystemSetting::where('key', 'language')->first();
        if ($language) {
            $data['language'] = $language->value ?? 'en';
        }

        // dimension
        $dimension = SystemSetting::where('key', 'dimension')->first();
        if ($dimension) {
            $data['dimension'] = $dimension->value ?? 'cm';
        }

        // weight
        $weight = SystemSetting::where('key', 'weight')->first();
        if ($weight) {
            $data['weight'] = $weight->value ?? 'kg';
        }

        // timezone
        $timezone = SystemSetting::where('key', 'timezone')->first();
        if ($timezone) {
            $data['timezone'] = $timezone->value ?? 'UTC';
        }

        // time_format
        $timeFormat = SystemSetting::where('key', 'time_format')->first();
        if ($timeFormat) {
            $data['time_format'] = $timeFormat->value ?? 'AM/PM';
        }

        // date_format
        $dateFormat = SystemSetting::where('key', 'date_format')->first();
        if ($dateFormat) {
            $data['date_format'] = $dateFormat->value ?? 'DD/MM/YYYY';
        }


        return response()->json([
            'success' => true,
            'statusCode' => 200,
            'message' => 'App details.',
            'data' => $data,
        ], 200);
    }
}
