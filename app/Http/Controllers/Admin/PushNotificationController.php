<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PushNotificationController extends Controller
{
    //index
    public function index()
    {
        return view('admin.push-notification.index');
    }

    // new
    public function new()
    {
        return view('admin.push-notification.new');
    }
}
