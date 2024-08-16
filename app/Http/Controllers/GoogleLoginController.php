<?php

namespace App\Http\Controllers;

use App\Models\SocialLoginSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;

class GoogleLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $google_client_id = SocialLoginSetting::where('key', 'google_client_id')->first();
            $google_secret_id = SocialLoginSetting::where('key', 'google_secret_id')->first();
            $google_redirect_uri = SocialLoginSetting::where('key', 'google_redirect_uri')->first();

            if (!$google_client_id || !$google_secret_id || !$google_redirect_uri) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Google configuration is incomplete.'
                ], 422);
            }

            // Load as config
            config([
                'services.google.client_id' => $google_client_id->value,
                'services.google.client_secret' => $google_secret_id->value,
                'services.google.redirect' => $google_redirect_uri->value,
            ]);
        }

        return Socialite::driver($provider)->stateless()->redirect();
    }

    public function handleProviderCallback($provider, Request $request)
    {
        if ($provider == 'google') {
            // Get config from SocialLoginSetting
            $google_client_id = SocialLoginSetting::where('key', 'google_client_id')->first();
            $google_secret_id = SocialLoginSetting::where('key', 'google_secret_id')->first();
            $google_redirect_uri = SocialLoginSetting::where('key', 'google_redirect_uri')->first();

            if (!$google_client_id || !$google_secret_id || !$google_redirect_uri) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Google configuration is incomplete.'
                ], 422);
            }

            // Load as config
            config([
                'services.google.client_id' => $google_client_id->value,
                'services.google.client_secret' => $google_secret_id->value,
                'services.google.redirect' => $google_redirect_uri->value,
            ]);
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

        // Check if the request expects a JSON response (e.g., from a mobile client)
        if ($request->expectsJson()) {
            return response()->json([
                'success' => true,
                'statusCode' => 200,
                'token' => $token,
                'user' => $user,
                'message' => 'Logged in successfully.',
            ], 200);
        } else {
            // Redirect for web clients
            return redirect()->route('index')->with('success', 'Logged in successfully.');
        }
    }
}
