<?php

namespace App\Http\Controllers;

use App\Services\EmailTemplateService;
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
        $placeholders = [
            'Customer' => $customer->name,
            'Company name' => 'Your Company',
            'services' => 'premium services',
            'Your name' => 'Support Team',
        ];

        $template = $this->emailTemplateService->getTemplate('Welcome Email', $placeholders);

        Mail::send([], [], function ($message) use ($customer, $template) {
            $message->to($customer->email)
                ->subject($template['subject'])
                ->setBody($template['body'], 'text/html');
        });
    }
}
