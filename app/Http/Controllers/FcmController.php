<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;


class FcmController extends Controller
{

    // For sending notification
    public function sendPushNotificationToUser($user_id, $title, $body)
    {

        $user = User::find($user_id);
        $deviceToken = $user->fcm_token;

        if (!$deviceToken) {
            return  0;
        }

        $keyFilePath = storage_path('app/firebase/key.json'); // Path to your service account key file

        $token = $this->getAccessToken($keyFilePath);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/v1/projects/point-delivery-3f719/messages:send', [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'android' => [
                    'priority' => 'high',
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => $title,
                                'body'  => $body,
                            ],
                            'sound' => 'default',
                            'badge' => 1,
                            'content_available' => true,
                        ],
                    ],
                ],
            ],
        ]);

        // return $response->json();
        if ($response->successful()) {
            return 1;
        } else {
            return 0;
        }
    }

    // For test api
    public function sendFcmNotification(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string',
            'body' => 'required|string',
        ]);

        $user = User::find($request->user_id);
        $deviceToken = $user->fcm_token;

        if (!$deviceToken) {
            return response()->json(['message' => 'User does not have a device token'], 400);
        }

        // $deviceToken = 'device_token_here'; // Replace with the actual device token
        $title = $request->title;
        $body = $request->body;


        $keyFilePath = storage_path('app/firebase/key.json'); // Path to your service account key file

        $token = $this->getAccessToken($keyFilePath);

        $response = Http::withHeaders([
            'Authorization' => 'Bearer ' . $token,
            'Content-Type'  => 'application/json',
        ])->post('https://fcm.googleapis.com/v1/projects/point-delivery-3f719/messages:send', [
            'message' => [
                'token' => $deviceToken,
                'notification' => [
                    'title' => $title,
                    'body'  => $body,
                ],
                'android' => [
                    'priority' => 'high',
                ],
                'apns' => [
                    'payload' => [
                        'aps' => [
                            'alert' => [
                                'title' => $title,
                                'body'  => $body,
                            ],
                            'sound' => 'default',
                            'badge' => 1,
                            'content_available' => true,
                        ],
                    ],
                ],
            ],
        ]);

        // return $response->json();
        if ($response->successful()) {
            return response()->json(['message' => 'Notification sent successfully'], 200);
        } else {
            return response()->json(['message' => 'Failed to send notification'], 500);
        }
    }

    private function getAccessToken($keyFilePath)
    {
        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type'    => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'     => $this->generateJwt($keyFilePath),
            ],
        ]);

        $data = json_decode($response->getBody()->getContents(), true);
        return $data['access_token'];
    }

    private function generateJwt($keyFilePath)
    {
        $key = json_decode(file_get_contents($keyFilePath), true);
        $now = time();
        $payload = [
            'iss' => $key['client_email'], // The issuer is the client email from the service account
            'sub' => $key['client_email'], // The subject is also the client email
            'aud' => 'https://oauth2.googleapis.com/token', // The audience is the Google OAuth token endpoint
            'iat' => $now,
            'exp' => $now + 3600, // Token valid for 1 hour
            'scope' => 'https://www.googleapis.com/auth/firebase.messaging', // Correct scope for FCM
        ];

        $jwt = JWT::encode($payload, $key['private_key'], 'RS256');
        return $jwt;
    }
}
