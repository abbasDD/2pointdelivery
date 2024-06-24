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

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUserNotificationRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(UserNotification $userNotification)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(UserNotification $userNotification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUserNotificationRequest $request, UserNotification $userNotification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(UserNotification $userNotification)
    {
        //
    }
}
