<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHelperRequest;
use App\Http\Requests\UpdateHelperRequest;
use App\Models\Booking;

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

        $helper_id = 0;
        // Get helper_id from Helper
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        // No Helper found for this user_id
        if ($helper) {
            $helper_id = $helper->id;
        }

        // Statistics
        $satistics = [
            'total_bookings' => Booking::where('user_id', $helper_id)->count(),
            'pending_bookings' => Booking::where('user_id', $helper_id)->where('status', 'pending')->count(),
            'cancelled_bookings' => Booking::where('user_id', $helper_id)->where('status', 'cancelled')->count(),
            'unpaid_bookings' => Booking::where('user_id', $helper_id)->where('payment_status', 'unpaid')->count(),
        ];

        $bookings = Booking::where('status', 'pending')
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')
            ->take(10)->get();

        return view('helper.index', compact('bookings', 'satistics'));
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
