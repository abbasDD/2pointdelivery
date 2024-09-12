<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Chat;
use App\Models\Client;
use App\Models\Helper;
use App\Models\Message;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    public function index(Request $request)
    {
        $chat = Chat::find($request->id);
        if (!$chat) {
            return response()->json(['success' => false, 'data' => 'Unable to find chat']);
        }
        $messages = $chat->messages()->orderBy('created_at', 'asc')->get();

        if ($chat->user1_id == Auth::user()->id) {
            $otherUser = User::findOrFail($chat->user2_id);
            if ($otherUser->client_enabled) {
                $otherUserInfo = Client::where('user_id', $otherUser->id)->first();
            } else {
                $otherUserInfo = Helper::where('user_id', $otherUser->id)->first();
            }
            // Check if user is admin
            if ($otherUser->user_type == 'admin') {
                $otherUserInfo = Admin::where('user_id', $otherUser->id)->first();
            }
        } else {
            $otherUser = User::findOrFail($chat->user1_id);
            if ($otherUser->client_enabled) {
                $otherUserInfo = Client::where('user_id', $otherUser->id)->first();
            } else {
                $otherUserInfo = Helper::where('user_id', $otherUser->id)->first();
            }
            // Check if user is admin
            if ($otherUser->user_type == 'admin') {
                $otherUserInfo = Admin::where('user_id', $otherUser->id)->first();
            }
        }

        // Return a json object
        return response()->json(['success' => true, 'otherUserInfo' => $otherUserInfo, 'data' => $messages]);
    }

    public function store(Request $request)
    {
        $chat = Chat::find($request->chat_id);

        if (!$chat) {
            return response()->json(['success' => false, 'data' => 'Unable to find chat']);
        }

        if ($chat->user1_id != Auth::user()->id && $chat->user2_id != Auth::user()->id) {
            return response()->json(['success' => false, 'data' => 'Unable to send message']);
        }

        $message = Message::create([
            'chat_id' => $request->chat_id,
            'sender_id' => Auth::user()->id,
            'message' => $request->message,
        ]);

        // Make chat read 0
        $chat->update(['is_read' => 0]);

        // Call notificaion client to send notification
        app('notificationHelper')->sendNotification(Auth::user()->id, $chat->user1_id == Auth::user()->id ? $chat->user2_id : $chat->user1_id, 'helper', 'chat', $request->chat_id, 'New Message', 'New message from ' . Auth::user()->first_name . ' ' . Auth::user()->last_name);

        // Return a json object
        return response()->json(['success' => true, 'data' => $message]);
    }
}
