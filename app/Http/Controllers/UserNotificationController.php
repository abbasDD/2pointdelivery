<?php

namespace App\Http\Controllers;

use App\Models\UserNotification;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

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
        $notifications = UserNotification::where('receiver_user_id', Auth::user()->id)
            ->where('receiver_user_type', session('login_type') ?? 'client')
            ->orderBy('created_at', 'asc')->take(10)->get();
        $unread_notification = UserNotification::where('receiver_user_id', Auth::user()->id)
            ->where('receiver_user_type', session('login_type') ?? 'client')
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

        // Mark all as read
        UserNotification::where('receiver_user_id', Auth::user()->id)->where('receiver_user_type', session('login_type') ?? 'client')->update(['read' => 1]);
        // return response()->json($notifications);
        return redirect()->back()->with('success', 'All notifications marked as read');
    }

    // notificationRedirect
    public function notificationRedirect($id)
    {
        $notification = UserNotification::where('id', $id)->first();
        if (!$notification) {
            return redirect()->back()->with('error', 'Unable to redirect notification');
        }

        $notification->read = 1;
        $notification->save();

        // dd(session('login_type'));

        // Check notificationType
        $notificationType = $notification->type;
        // dd($notificationType);

        switch ($notificationType) {
            case 'booking':
                // Booking
                if (session('login_type') == 'client') {
                    return redirect()->route('client.booking.show', $notification->reference_id);
                }
                return redirect()->route('helper.booking.show', $notification->reference_id);
                break;
            case 'team_invitation':
                // team_inviation
                if (session('login_type') == 'client') {
                    return redirect()->route('client.invitations');
                }
                return redirect()->route('helper.invitations');
                break;
            case 'kyc_detail':
                // kyc_detail
                if (session('login_type') == 'admin') {
                    return redirect()->route('admin.kycDetail.show', $notification->reference_id);
                }
                if (session('login_type') == 'helper') {
                    return redirect()->route('helper.kyc_details');
                }
                return redirect()->route('client.kyc_details');
                break;
            case 'user_registered':
                // user_registered
                if (session('login_type') == 'admin') {
                    // Get User
                    $user = User::where('id', $notification->reference_id)->first();
                    if ($user) {
                        if ($user->client_enabled == 1) {
                            return redirect()->route('admin.clients');
                        }
                        return redirect()->route('admin.newHelpers');
                    }
                    return redirect()->route('admin.newHelpers');
                }
                // Redirect back with error
                return redirect()->back()->with('error', 'Unable to redirect notification');
                break;
            case 'helper_status':
                // helper_status
                if (session('login_type') == 'helper') {
                    return redirect()->route('helper.index');
                }
                return redirect()->back()->with('error', 'Unable to redirect notification');
                break;
            case 'helper_vehicle_status':
                // helper_vehicle_status
                if (session('login_type') == 'helper') {
                    return redirect()->route('helper.index');
                }
                return redirect()->back()->with('error', 'Unable to redirect notification');
                break;
            case 'chat':
                // chat
                if (session('login_type') == 'admin') {
                    return redirect()->route('admin.chats');
                }
                if (session('login_type') == 'helper') {
                    return redirect()->route('helper.chats');
                }
                return redirect()->route('client.chats');
                break;
            case 'helper_bank_account_status':
                // helper_bank_account_status
                if (session('login_type') == 'helper') {
                    return redirect()->route('helper.wallet');
                }
                return redirect()->back()->with('error', 'Unable to redirect notification');
                break;
            case 'wallet':
                // wallet
                if (session('login_type') == 'admin') {
                    return redirect()->route('admin.wallet');
                }
                if (session('login_type') == 'helper') {
                    return redirect()->route('helper.wallet');
                }
                return redirect()->route('client.wallet');
                break;
            default:
                // default
                return redirect()->back()->with('error', 'Unable to redirect notification');
                break;
        }

        return redirect()->back()->with('success', 'Notification marked as read');
    }
}
