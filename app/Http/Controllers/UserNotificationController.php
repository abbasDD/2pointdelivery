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
        $notifications = UserNotification::where('receiver_user_id', auth()->user()->id)->orderBy('created_at', 'desc')->get();
        return response()->json($notifications);
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
}
