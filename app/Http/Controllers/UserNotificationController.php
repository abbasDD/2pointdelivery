<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Http\Requests\StoreUserNotificationRequest;
use App\Http\Requests\UpdateUserNotificationRequest;

class UserNotificationController extends Controller
{
    /**
     * Get Authenticated User Notifications
     */
    public function index()
    {
        if (!auth()->user()) {
            return response()->json([]);
        }
        // Get only 10
        $notifications = UserNotification::where('receiver_user_id', auth()->user()->id)
            ->where('receiver_user_type', session('user_type') ?? 'client')
            ->orderBy('created_at', 'asc')->take(10)->get();
        $unread_notification = UserNotification::where('receiver_user_id', auth()->user()->id)
            ->where('receiver_user_type', session('user_type') ?? 'client')
            ->where('read', 0)->count();
        $data = [
            'notifications' => $notifications,
            'unread_notification' => $unread_notification
        ];
        return response()->json($data);
    }

    // Mark all as read
    public function markAllAsRead()
    {
        if (!auth()->user()) {
            return response()->json([]);
        }
        $notifications = UserNotification::where('receiver_user_id', auth()->user()->id)->update(['read' => 1]);
        // return response()->json($notifications);
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    // notificationRedirect
    public function notificationRedirect($id)
    {
        $notification = UserNotification::where('id', $id)->first();
        $notification->read = 1;
        $notification->save();

        // dd(session('user_type'));

        // Check notificationType
        $notificationType = $notification->type;
        // dd($notificationType);

        switch ($notificationType) {
            case 'booking':
                // Booking
                if (session('user_type') == 'client') {
                    return redirect()->route('client.booking.show', $notification->reference_id);
                }
                return redirect()->route('helper.booking.show', $notification->reference_id);
                break;
            case 'team_invitation':
                // team_inviation
                if (session('user_type') == 'client') {
                    return redirect()->route('client.teams.show', $notification->reference_id);
                }
                return redirect()->route('helper.invitations.show', $notification->reference_id);
                break;
            default:
                // default
                return redirect()->back()->with('error', 'Unable to redirect notification');
                break;
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }
}
