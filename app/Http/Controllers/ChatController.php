<?php

namespace App\Http\Controllers;

use App\Models\Chat;
use App\Http\Requests\StoreChatRequest;
use App\Http\Requests\UpdateChatRequest;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class ChatController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function redirectChat()
    {
        if (Auth::user()->user_type == 'admin') {
            return redirect('/admin/chats');
        } else {
            return redirect('/client/chats');
        }
    }

    public function index(Request $request)
    {
        // Retrieve the user
        $user = User::findOrFail(Auth::user()->id);

        // Get the user's chats along with the other user in the chat
        $chats = $user->chats()->with('otherUser')->get();

        foreach ($chats as $chat) {
            $chat->last_message = $chat->messages()->latest()->first();
            if ($chat->user1_id == Auth::user()->id) {
                $otherUser = User::findOrFail($chat->user2_id);
                if ($otherUser->client_enabled) {
                    $chat->otherUserInfo = Client::where('user_id', $otherUser->id)->first();
                } else {
                    $chat->otherUserInfo = Helper::where('user_id', $otherUser->id)->first();
                }
                // Check if user is admin
                if ($otherUser->user_type == 'admin') {
                    $chat->otherUserInfo = Admin::where('user_id', $otherUser->id)->first();
                }
            } else {
                $otherUser = User::findOrFail($chat->user1_id);
                if ($otherUser->client_enabled) {
                    $chat->otherUserInfo = Client::where('user_id', $otherUser->id)->first();
                } else {
                    $chat->otherUserInfo = Helper::where('user_id', $otherUser->id)->first();
                }
                // Check if user is admin
                if ($otherUser->user_type == 'admin') {
                    $chat->otherUserInfo = Admin::where('user_id', $otherUser->id)->first();
                }
            }
        }
        // dd($chats[1]->otherUserInfo);
        // Pass the chat list to the view or return it as JSON
        // return response()->json($chats);
        return view('client.chats.index', ['chats' => $chats]);
    }

    public function create(Request $request)
    {
        // Retrieve the user
        $user = User::findOrFail($request->user_id);

        // Check if user exists
        if (!$user) {
            return response()->json(['success' => false, 'chat_id' => 0, 'message' => 'User not found']);
        }

        if ($user->user_type == 'admin') {
            $userInfo = Admin::where('user_id', $user->id)->first();
        }

        // Get User detail as per user type
        if ($user->client_enabled == 1) {
            $userInfo = Client::where('user_id', $user->id)->first();
        }

        if ($user->helper_enabled == 1) {
            $userInfo = Helper::where('user_id', $user->id)->first();
        }

        // Check chat between users already exists
        $chatExists = Chat::where('user1_id', $user->id)->where('user2_id', Auth::user()->id)->orWhere('user1_id', Auth::user()->id)->where('user2_id', $user->id)->first();

        if ($chatExists) {
            return response()->json(['success' => true, 'chat_id' => $chatExists->id, 'userInfo' => $userInfo, 'message' => 'Chat already exists']);
        }
        // Create chat between user_id and current user
        $chat = new Chat();
        $chat->user1_id = $user->id;
        $chat->user2_id = Auth::user()->id;
        $chat->save();

        return response()->json(['success' => true, 'chat_id' => $chat->id, 'userInfo' => $userInfo, 'message' => 'Chat created successfully']);
    }
}
