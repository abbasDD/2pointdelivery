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
            $socialLoginSetting = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$socialLoginSetting) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_secret_id')->first();
            if (!$socialLoginSetting) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
            if (!$socialLoginSetting) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
            }

            // Load as config
            config(['services.google.client_id' => $socialLoginSetting->value]);
            config(['services.google.client_secret' => $socialLoginSetting->value]);
            config(['services.google.redirect' => $socialLoginSetting->value]);
        }

        // dd($socialLoginSetting->value);

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider)
    {

        //  Check if provider is google
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $socialLoginSetting = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$socialLoginSetting) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client ID not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_secret_id')->first();
            if (!$socialLoginSetting) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google client secret not configured.'], 422);
            }

            $socialLoginSetting = SocialLoginSetting::where('key', 'google_redirect_uri')->first();
            if (!$socialLoginSetting) {
                return response()->json(['success' => false, 'statusCode' => 422, 'message' => 'Google redirect URL not configured.'], 422);
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
}
