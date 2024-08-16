<?php

namespace App\Http\Controllers;

use App\Models\SocialLoginSetting;
use App\Models\User;
use Illuminate\Http\Request;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Auth;
use Google_Client;

class GoogleLoginController extends Controller
{
    public function redirectToProvider($provider)
    {
        if ($provider == 'google') {
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
            $google_client_id = SocialLoginSetting::where('key', 'google_client_id')->first();
            if (!$google_client_id) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 422,
                    'message' => 'Google client ID not configured.',
                ], 422);
            }

            $token = $request->input('token');

            if (!$token) {
                return response()->json([
                    'success' => false,
                    'statusCode' => 400,
                    'message' => 'Token is required.',
                ], 400);
            }

            try {
                $client = new Google_Client(['client_id' => $google_client_id->value]);
                $payload = $client->verifyIdToken($token);

                if (!$payload) {
                    return response()->json([
                        'success' => false,
                        'statusCode' => 401,
                        'message' => 'Invalid ID token.',
                    ], 401);
                }

                $user = User::where('provider_id', $payload['sub'])->first();

                if (!$user) {
                    $user = User::create([
                        'name' => $payload['name'] ?? 'Unnamed',
                        'email' => $payload['email'] ?? null,
                        'provider_name' => $provider,
                        'provider_id' => $payload['sub'],
                        'password' => null,
                    ]);
                }

                Auth::login($user);
                $tokenResult = $user->createToken('2PointDeliveryJWTAuthenticationToken');
                $token = $tokenResult->accessToken;

                // return response()->json([
                //     'success' => true,
                //     'statusCode' => 200,
                //     'token' => $token,
                //     'user' => $user,
                //     'message' => 'Logged in successfully.',
                // ], 200);
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
