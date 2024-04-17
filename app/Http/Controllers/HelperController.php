<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHelperRequest;
use App\Http\Requests\UpdateHelperRequest;

class HelperController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application complete profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function complete_profile()
    {
        return view('helper.auth.complete_profile');
    }

    /**
     * Show the application complete profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_profile()
    {
        dd('update profile');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('helper.index');
    }


    public function kyc_details()
    {
        return view('helper.kyc_details');
    }

    public function bookings()
    {
        return view('helper.bookings');
    }


    public function settings()
    {
        return view('helper.settings');
    }

    public function edit_profile()
    {
        return view('helper.edit');
    }

    public function teams()
    {
        return view('helper.teams');
    }

    public function track_order()
    {
        return view('helper.track_order');
    }
}
