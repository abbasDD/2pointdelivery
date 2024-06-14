<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;

class EmailTemplateController extends Controller
{

    public function index()
    {
        // Get all email templates
        $welcomeEmail = EmailTemplate::where('slug', 'welcome-email')->first();
        $passwordResetEmail = EmailTemplate::where('slug', 'password-reset-email')->first();
        $bookingStatusEmail = EmailTemplate::where('slug', 'booking-status-email')->first();
        $deliveryNotificationEmail = EmailTemplate::where('slug', 'delivery-notification-email')->first();
        $feedbackEmail = EmailTemplate::where('slug', 'feedback-email')->first();
        $requestFeedbackEmail = EmailTemplate::where('slug', 'request-feedback-email')->first();
        $refundNotificationEmail = EmailTemplate::where('slug', 'refund-notification-email')->first();


        return view(
            'admin.emailTemplates.index',
            compact(
                'welcomeEmail',
                'passwordResetEmail',
                'bookingStatusEmail',
                'deliveryNotificationEmail',
                'feedbackEmail',
                'requestFeedbackEmail',
                'refundNotificationEmail'
            )
        );
    }

    // Store welcomeEmailStore
    public function welcomeEmailStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $welcomeEmail = EmailTemplate::where('slug', 'welcome-email')->first();

        if ($welcomeEmail) {
            $welcomeEmail->update($request->all());
        } else {
            $welcomeEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Welcome email updated successfully');
    }

    // Store passwordResetEmailStore
    public function passwordResetEmailStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $passwordResetEmail = EmailTemplate::where('slug', 'password-reset-email')->first();

        if ($passwordResetEmail) {
            $passwordResetEmail->update($request->all());
        } else {
            $passwordResetEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Password reset email updated successfully');
    }
}
