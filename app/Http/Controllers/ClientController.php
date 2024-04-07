<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreClientRequest;
use App\Http\Requests\UpdateClientRequest;
use Illuminate\Support\Facades\Auth;

class ClientController extends Controller
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
        // dd(Auth::user()->account_type);
        if (Auth::user()->account_type == 'company') {
            return view('client.auth.complete_company_profile');
        }

        return view('client.auth.complete_profile');
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
        return view('client.index');
    }

    public function kyc_details()
    {
        return view('client.kyc_details');
    }

    public function orders()
    {
        return view('client.orders');
    }

    public function invoices()
    {
        return view('client.invoices');
    }

    public function referrals()
    {
        return view('client.referrals');
    }

    public function settings()
    {
        return view('client.settings');
    }

    public function edit_profile()
    {
        return view('client.edit');
    }
}
