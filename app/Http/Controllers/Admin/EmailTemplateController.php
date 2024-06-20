<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailTemplate;
use Illuminate\Http\Request;
use Mews\Purifier\Facades\Purifier;

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
            $request['body'] = Purifier::clean($request->input('body'));
            $welcomeEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
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
            $request['body'] = Purifier::clean($request->input('body'));
            $passwordResetEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
            $passwordResetEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Password reset email updated successfully');
    }


    // bookingStatusStore
    public function bookingStatusStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $bookingStatusEmail = EmailTemplate::where('slug', 'booking-status-email')->first();

        if ($bookingStatusEmail) {
            $request['body'] = Purifier::clean($request->input('body'));
            $bookingStatusEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
            $bookingStatusEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Booking status email updated successfully');
    }
    // deliveryNotificationStore
    public function deliveryNotificationStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $deliveryNotificationEmail = EmailTemplate::where('slug', 'delivery-notification-email')->first();

        if ($deliveryNotificationEmail) {
            $request['body'] = Purifier::clean($request->input('body'));
            $deliveryNotificationEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
            $deliveryNotificationEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Delivery notification email updated successfully');
    }

    // feedbackEmailStore
    public function feedbackEmailStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $feedbackEmail = EmailTemplate::where('slug', 'feedback-email')->first();

        if ($feedbackEmail) {
            $request['body'] = Purifier::clean($request->input('body'));
            $feedbackEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
            $feedbackEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Feedback email updated successfully');
    }

    // requestFeedbackEmailStore
    public function requestFeedbackEmailStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $requestFeedbackEmail = EmailTemplate::where('slug', 'request-feedback-email')->first();

        if ($requestFeedbackEmail) {
            $request['body'] = Purifier::clean($request->input('body'));
            $requestFeedbackEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
            $requestFeedbackEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Request feedback email updated successfully');
    }

    // refundNotificationEmailStore
    public function refundNotificationEmailStore(Request $request)
    {
        $request->validate([
            'subject' => 'required',
            'body' => 'required',
        ]);

        $refundNotificationEmail = EmailTemplate::where('slug', 'refund-notification-email')->first();

        if ($refundNotificationEmail) {
            $request['body'] = Purifier::clean($request->input('body'));
            $refundNotificationEmail->update($request->all());
        } else {
            $request['body'] = Purifier::clean($request->input('body'));
            $refundNotificationEmail = EmailTemplate::create($request->all());
        }

        return redirect()->back()->with('success', 'Refund notification email updated successfully');
    }
}
