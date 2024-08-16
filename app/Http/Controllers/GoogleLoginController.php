<?php

namespace App\Http\Controllers;

use App\Models\SocialLoginSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Hash;

class GoogleLoginController extends Controller
{

    public function redirectToProvider($provider)
    {
        //  Check if provider is google
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $google_client_id = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$google_client_id) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
            }

            $google_secret_id = SocialLoginSetting::where('key', 'google_secret_id')->first();
            if (!$google_secret_id) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
            }

            $google_redirect_uri = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
            if (!$google_redirect_uri) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
            }

            // Load as config
            config(['services.google.client_id' => $google_client_id->value]);
            config(['services.google.client_secret' => $google_secret_id->value]);
            config(['services.google.redirect' => $google_redirect_uri->value]);
        }

        // dd($socialLoginSetting->value);

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {

        //  Check if provider is google
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $google_client_id = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$google_client_id) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
            }

            $google_secret_id = SocialLoginSetting::where('key', 'google_secret_id')->first();
            if (!$google_secret_id) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
            }

            $google_redirect_uri = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
            if (!$google_redirect_uri) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
            }

            // Load as config
            config(['services.google.client_id' => $google_client_id->value]);
            config(['services.google.client_secret' => $google_secret_id->value]);
            config(['services.google.redirect' => $google_redirect_uri->value]);
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
}
