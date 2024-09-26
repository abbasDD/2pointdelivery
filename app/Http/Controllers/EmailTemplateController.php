<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\SmtpSetting;
use App\Models\User;
use App\Services\EmailTemplateService;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class EmailTemplateController extends Controller
{

    protected $emailTemplateService;

    public function __construct(EmailTemplateService $emailTemplateService)
    {
        $this->emailTemplateService = $emailTemplateService;
    }


    public function sendWelcomeEmail($customer)
    {
        try {
            $placeholders = [
                'Customer' => $customer->email,
                'Company name' => '2 Point Delivery',
                'services' => 'premium services',
                'Your name' => 'Support Team',
            ];

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

            $template = $this->emailTemplateService->getTemplate('Welcome Email', $placeholders);

            if (!$template) {
                return false;
            }

            Mail::send([], [], function ($message) use ($customer, $template) {
                $message->to($customer->email)
                    ->subject($template['subject'])
                    ->html($template['body'], 'text/html');
            });

            return true;
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return false;
        }
    }


    // Booking Status Email
    public function bookingStatusEmail($booking)
    {
        $client_name = 'Customer';

        $client = Client::find($booking->client_id);
        if (!$client) {
            return false;
        }

        // Get user
        $user = User::find($client->user_id);
        if (!$user) {
            return false;
        }


        $client_name = $client->first_name . ' ' . $client->last_name;

        $placeholders = [
            'Customer name' => $client_name,
            'Tracking number' => $booking->uuid,
            'Your name' => '2 Point Delivery',
        ];

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

        // if config is invalid then send failed response

        if (!Mail::hasSwiftMailer()) {
            return false;
        }

        try {
            $template = $this->emailTemplateService->getTemplate('Booking Status Email', $placeholders);

            if (!$template) {
                return false;
            }

            $useremail = $user->email;

            Mail::send([], [], function ($message) use ($template, $useremail) {
                $message->to($useremail)
                    ->subject($template['subject'])
                    ->html($template['body'], 'text/html');
            });

            return true;
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return false;
        }
    }


    // Send push notification Email
    public function pushNotificationEmail($user_id, $title, $body)
    {
        $user = User::find($user_id);

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

        try {
            Mail::send([], [], function ($message) use ($user, $title, $body) {
                $message->to($user->email)
                    ->subject($title)
                    ->html($body, 'text/html');
            });

            return true;
        } catch (\Exception $e) {
            // Log::error($e->getMessage());
            return false;
        }
    }
}
