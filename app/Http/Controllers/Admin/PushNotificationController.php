<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FcmController;
use App\Jobs\SendNotificationsJob;
use App\Models\AdminPushNotification;
use App\Models\User;
use Illuminate\Http\Request;

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
        SendNotificationsJob::dispatch($users, $request->input('title'), $request->input('body'));

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
                $users = User::where('is_active', 1)->where('fcm_token', '!=', null)->get();
                break;
            case 'helpers':
                $users = User::where('is_active', 1)->where('helper_enabled', 1)->where('fcm_token', '!=', null)->get();
                break;
            case 'clients':
                $users = User::where('is_active', 1)->where('client_enabled', 1)->where('fcm_token', '!=', null)->get();
                break;
            default:
                $users = User::where('id', $notification->user_id)->get();
                break;
        }

        // Dispatch job to send notifications
        SendNotificationsJob::dispatch($users, $notification->title, $notification->body);

        return redirect()->route('admin.pushNotification')->with('success', 'Notification is being sent in the background');
    }
}
