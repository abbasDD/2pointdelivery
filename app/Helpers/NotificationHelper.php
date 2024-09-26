<?php

namespace App\Helpers;

use App\Models\Booking;
use App\Models\SmtpSetting;
use App\Models\User;
use App\Models\UserNotification;
use App\Services\EmailTemplateService;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Http;
use Firebase\JWT\JWT;
use Illuminate\Support\Facades\Mail;

class NotificationHelper
{


    public static function sendNotification($sender_user_id, $receiver_user_id, $receiver_user_type, $notificationType, $reference_id, $title, $content)
    {
        $user = User::where('id', $receiver_user_id)->first();
        if (!$user) {
            return false;
        }

        // Store Notification
        self::storeNotification($sender_user_id, $receiver_user_id, $receiver_user_type, $notificationType, $reference_id, $title, $content);

        // Send fcm notification
        self::sendPushNotificationToUser($receiver_user_id, $title, $content);

        // Switch case for $notificationType to send Email
        switch ($notificationType) {
            case 'booking':
                // Booking
                $booking = Booking::where('id', $reference_id)->with('serviceCategory')->first();
                if (!$booking) {
                    break;
                }

                $placeholders = [
                    'Customer name' => $user->email,
                    'Service category' => $booking->serviceCategory->name,
                    'Tracking number' => $booking->uuid,
                    'Your name' => 'Support Team',
                ];

                $emailTemplateService = new EmailTemplateService();

                $template = $emailTemplateService->getTemplate('Booking Status Email', $placeholders);

                if (!$template) {
                    $template['subject'] = 'Booking Status Email';
                    $template['body'] = 'We have detected that a booking status has changed. Please check the details.';
                }
                self::sendEmail($template, $user);
                break;
            case 'team_invitation':
                // team_inviation
                $template['subject'] = 'Team Invitation';
                $template['body'] = 'A user has invited you to join the team. Please check the details.';
                self::sendEmail($template, $user);
                break;
            case 'kyc_detail':
                // kyc_detail
                break;
            case 'user_registered':
                // user_registered
                $placeholders = [
                    'Customer' => $user->email,
                    'Company name' => '2 Point Delivery',
                    'services' => 'premium services',
                    'Your name' => 'Support Team',
                ];

                $emailTemplateService = new EmailTemplateService();

                $template = $emailTemplateService->getTemplate('Welcome Email', $placeholders);

                if (!$template) {
                    $template['subject'] = 'Welcome Email';
                    $template['body'] = 'Welcome Email';
                }

                self::sendEmail($template, $user);
                break;
            case 'helper_status':
                // helper_status
                $template['subject'] = 'Helper Status Change';
                $template['body'] = 'We have detected that the helper status has changed. Please check the details.';
                self::sendEmail($template, $user);
                break;
            case 'helper_vehicle_status':
                // helper_vehicle_status
                $template['subject'] = 'Helper Vehicle Status Change';
                $template['body'] = 'We have detected that the helper vehicle status has changed. Please check the details.';
                self::sendEmail($template, $user);
                break;
            case 'chat':
                // chat
                $template['subject'] = 'Chat';
                $template['body'] = 'You received a new message.';
                self::sendEmail($template, $user);
                break;
            case 'helper_bank_account_status':
                // helper_bank_account_status
                $template['subject'] = 'Helper Bank Account Status Change';
                $template['body'] = 'We have detected that the helper bank account status has changed. Please check the details.';
                self::sendEmail($template, $user);
                break;
            case 'wallet':
                // wallet
                $template['subject'] = $title;
                $template['body'] = $content;
                self::sendEmail($template, $user);
                break;
            default:
                // default
                break;
        }
    }

    // storeNotification
    private static function storeNotification($sender_user_id, $receiver_user_id, $receiver_user_type, $notificationType, $reference_id, $title, $content)
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
    private static function sendPushNotificationToUser($user_id, $title, $body)
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

    private static function getAccessToken($keyFilePath)
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

    private static function generateJwt($keyFilePath)
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


    public static function sendEmail($template, $user)
    {
        try {
            $smtpSettings = SmtpSetting::get();
            if ($smtpSettings->isEmpty()) {
                return false;
            }

            $smtpSettingEnabled = $smtpSettings->where('key', 'smtp_enabled')->first();
            if ($smtpSettingEnabled->value == 'no') {
                return false;
            }

            // Configure mailer with the SMTP settings
            config([
                'mail.mailers.smtp.transport' => 'smtp',
                'mail.mailers.smtp.host' => $smtpSettings->where('key', 'smtp_host')->first()->value ?? '',
                'mail.mailers.smtp.port' => $smtpSettings->where('key', 'smtp_port')->first()->value ?? 587,
                'mail.mailers.smtp.encryption' => $smtpSettings->where('key', 'smtp_encryption')->first()->value ?? '',
                'mail.mailers.smtp.username' => $smtpSettings->where('key', 'smtp_username')->first()->value ?? '',
                'mail.mailers.smtp.password' => $smtpSettings->where('key', 'smtp_password')->first()->value ?? '',
                'mail.from.address' => $smtpSettings->where('key', 'smtp_from_email')->first()->value ?? '',
                'mail.from.name' => $smtpSettings->where('key', 'smtp_from_name')->first()->value ?? '',
            ]);

            Mail::send([], [], function ($message) use ($user, $template) {
                $message->to($user->email)
                    ->subject($template['subject'])
                    ->html($template['body'], 'text/html');
            });

            return true;
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return false;
        }
    }
}
