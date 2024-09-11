<?php

namespace App\Helpers;


use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;

class NotificationHelper
{
    public static function sendNotification($sender_user_id, $receiver_user_id, $receiver_user_type, $notificationType, $reference_id, $title, $content)
    {
        // Store Notification
        self::storeNotification($sender_user_id, $receiver_user_id, $receiver_user_type, $notificationType, $reference_id, $title, $content);

        if ($receiver_user_type != 'admin') {
            // Send fcm notification
            self::sendPushNotificationToUser($receiver_user_id, $title, $content);
        }

        // Switch case for $notificationType to send Email
        switch ($notificationType) {
            case 'booking':
                // Booking
                break;
            case 'team_invitation':
                // team_inviation
                break;
            case 'kyc_detail':
                // kyc_detail
                break;
            case 'user_registered':
                // user_registered
                break;
            case 'helper_status':
                // helper_status
                break;
            case 'helper_vehicle_status':
                // helper_vehicle_status
                break;
            case 'chat':
                // chat
                break;
            case 'helper_bank_account_status':
                // helper_bank_account_status
                break;
            default:
                // default
                break;
        }
    }

    // storeNotification
    private function storeNotification($sender_user_id, $receiver_user_id, $receiver_user_type, $notificationType, $reference_id, $title, $content)
    {

        // Store Notification
        UserNotification::create([
            'sender_user_id' => $sender_user_id,
            'receiver_user_id' => $receiver_user_id,
            'receiver_user_type' => $receiver_user_type,
            'type' => $notificationType,
            'reference_id' => $reference_id,
            'title' => $title,
            'content' => $content,
            'read' => 0
        ]);
    }

    // For sending notification
    private function sendPushNotificationToUser($user_id, $title, $body)
    {

        $user = User::find($user_id);
        $deviceToken = $user->fcm_token;

        if (!$deviceToken) {
            return  0;
        }

        $keyFilePath = storage_path('app/firebase/key.json'); // Path to your service account key file

        $token = self::getAccessToken($keyFilePath);

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



    private function getAccessToken($keyFilePath)
    {
        $client = new Client();
        $response = $client->post('https://oauth2.googleapis.com/token', [
            'form_params' => [
                'grant_type'    => 'urn:ietf:params:oauth:grant-type:jwt-bearer',
                'assertion'     => self::generateJwt($keyFilePath),
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
