<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\EmailTemplateController;
use App\Http\Controllers\FcmController;
use App\Jobs\SendNotificationsJob;
use App\Models\AdminPushNotification;
use App\Models\User;
use App\Services\EmailTemplateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Mews\Purifier\Facades\Purifier;

class PushNotificationController extends Controller
{
    // FCM Notification Cotroller
    private $fcm;

    public function __construct(FcmController $fcm)
    {
        $this->fcm = $fcm;
    }

    //index
    public function index()
    {

        // Get all notifications
        $notifications = AdminPushNotification::orderBy('id', 'desc')->get();

        return view('admin.push-notification.index', compact('notifications'));
    }

    // new
    public function new()
    {
        return view('admin.push-notification.new');
    }

    // send
    public function send(Request $request)
    {
        // Validate request
        $request->validate([
            'user_id' => 'required',
            'title' => 'required|string|max:255',
            'body' => 'required'
        ]);

        if ($request->input('send_email')) {
            // store as true
            $request->merge(['send_email' => 1]);
        }

        // Determine the users to notify
        switch ($request->input('user_id')) {
            case 'all':
                $users = User::where('is_active', 1)->where('fcm_token', '!=', null)->get();
                break;
            case 'helpers':
                $users = User::where('is_active', 1)->where('helper_enabled', 1)->where('fcm_token', '!=', null)->get();
                break;
            case 'clients':
                $users = User::where('is_active', 1)->where('client_enabled', 1)->where('fcm_token', '!=', null)->get();
                break;
            default:
                $users = User::where('id', $request->input('user_id'))->get();
                break;
        }

        // Store in AdminPushNotification
        AdminPushNotification::create([
            'user_id' => $request->input('user_id'),
            'title' => $request->input('title'),
            'body' => $request->input('body'),
            'send_email' => $request->input('send_email') ?? 0
        ]);

        // Dispatch job to send notifications
        // SendNotificationsJob::dispatch($users, $request->input('title'), $request->input('body'));
        // $fcm = new FcmController();

        // foreach ($users as $user) {

        //     if ($user->fcm_token != null) {
        //         $fcm->sendPushNotificationToUser($user->id, $request->input('title'), Purifier::clean($request->input('body')));
        //     }
        //     // Send email pushNotificationEmail
        //     $emailTemplateService = new EmailTemplateService();
        //     $emailTemplateController = new EmailTemplateController($emailTemplateService);
        //     $emailTemplateController->pushNotificationEmail($user->id, $request->input('title'), Purifier::clean($request->input('body')));
        // }


        // Send exec call
        $this->sendExecCall($users, $request->input('title'), $request->input('body'));

        return redirect()->route('admin.pushNotification')->with('success', 'Notification is being sent in the background');
    }

    // resend
    public function resend($id)
    {

        // Check if id exist in request
        $notification = AdminPushNotification::where('id', $id)->first();

        if (!$notification) {
            return redirect()->route('admin.pushNotification')->with('error', 'Notification not found');
        }

        // Determine the users to notify
        switch ($notification->user_id) {
            case 'all':
                $users = User::where('is_active', 1)->get();
                break;
            case 'helpers':
                $users = User::where('is_active', 1)->where('helper_enabled', 1)->get();
                break;
            case 'clients':
                $users = User::where('is_active', 1)->where('client_enabled', 1)->get();
                break;
            default:
                $users = User::where('id', $notification->user_id)->get();
                break;
        }

        // Dispatch job to send notifications
        // SendNotificationsJob::dispatch($users, $notification->title, $notification->body);

        // $fcm = new FcmController();

        // foreach ($users as $user) {

        //     if ($user->fcm_token != null) {
        //         $fcm->sendPushNotificationToUser($user->id, $notification->title, Purifier::clean($notification->body));
        //     }
        //     // Send email pushNotificationEmail
        //     $emailTemplateService = new EmailTemplateService();
        //     $emailTemplateController = new EmailTemplateController($emailTemplateService);
        //     $emailTemplateController->pushNotificationEmail($user->id, $notification->title, Purifier::clean($notification->body));
        // }

        // Send exec call
        $this->sendExecCall($users, $notification->title, $notification->body);

        return redirect()->route('admin.pushNotification')->with('success', 'Notification is being sent in the background');
    }

    // send using exec call
    public function sendExecCall($users, $title, $body)
    {
        // Email-sending logic written in the same function
        $code = <<<EOL
            <?php
                \$fcm = new FcmController();

                foreach (\$users as \$user) {

                    if (\$user->fcm_token != null) {
                        \$fcm->sendPushNotificationToUser(\$user->id, \$title, Purifier::clean(\$body));
                    }
                    // Send email pushNotificationEmail
                    \$emailTemplateService = new EmailTemplateService();
                    \$emailTemplateController = new EmailTemplateController(\$emailTemplateService);
                    \$emailTemplateController->pushNotificationEmail(\$user->id, \$title, Purifier::clean(\$body));
                }

                ?>
            EOL;

        // Save the code as a temporary file
        $tempFile = tempnam(sys_get_temp_dir(), 'send_emails_') . '.php';
        file_put_contents($tempFile, $code);

        // Run the temporary file in the background using exec()
        exec("php $tempFile > /dev/null 2>&1 &");

        return true;
    }
}
