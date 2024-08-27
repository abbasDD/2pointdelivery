<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class WalletClientController extends Controller
{
    // wallet index 
    public function index()
    {
        // amount_spend
        $balance['amount_spend'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->sum('amount');

        // unpaid_amount
        $balance['unpaid_amount'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->where('status', 'pending')->sum('amount');

        // amount_refunded
        $balance['amount_refunded'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->where('status', 'refunded')->sum('amount');

        return view('client.wallet.index', compact('balance'));
    }
}
