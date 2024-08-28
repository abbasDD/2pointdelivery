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
        if ($provider === 'google') {
            // Retrieve Google credentials from the database
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

            // Set Google client credentials
            config([
                'services.google.client_id' => $google_client_id->value,
                'services.google.client_secret' => $google_secret_id->value,
                'services.google.redirect' => $google_redirect_uri->value,
            ]);

            // Redirect to Google for authentication
            return Socialite::driver($provider)->stateless()->redirect();
        }

        return response()->json([
            'success' => false,
            'statusCode' => 400,
            'message' => 'Invalid provider.',
        ], 400);
    }

    public function handleProviderCallback($provider)
{
    if ($provider === 'google') {
        // Retrieve Google credentials from the database
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

        // Set Google client credentials
        config([
            'services.google.client_id' => $google_client_id->value,
            'services.google.client_secret' => $google_secret_id->value,
            'services.google.redirect' => $google_redirect_uri->value,
        ]);

        try {
            // Retrieve the user information from Google
            $socialiteUser = Socialite::driver($provider)->stateless()->user();

            // Check if the email already exists in your database
            $user = User::where('email', $socialiteUser->getEmail())->first();

            if ($user) {
                // If user exists, update the provider information
                $user->update([
                    'provider_name' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                ]);
            } else {
                // Create a new user if one doesn't exist
                $user = User::create([
                    'name' => $socialiteUser->getName(),
                    'email' => $socialiteUser->getEmail(),
                    'user_type' => 'user',
                    'provider_name' => $provider,
                    'provider_id' => $socialiteUser->getId(),
                    'password' => null,
                ]);
            }

            // Log in the user
            Auth::login($user);

            // Return the response based on whether the request expects JSON
            if (request()->expectsJson()) {

                // Create a token for API authentication (using Laravel Passport)
            $tokenResult = $user->createToken('2PointDeliveryJWTAuthenticationToken');
            $token = $tokenResult->accessToken;

                return response()->json([
                    'success' => true,
                    'statusCode' => 200,
                    'token' => $token,
                    'user' => $user,
                    'message' => 'Logged in successfully.',
                ], 200);
            } else {
                return redirect()->route('index')->with('success', 'Logged in successfully.');
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'statusCode' => 500,
                'message' => 'Authentication failed: ' . $e->getMessage(),
            ], 500);
        }
    }

    return response()->json([
        'success' => false,
        'statusCode' => 400,
        'message' => 'Invalid provider.',
    ], 400);
}

}
