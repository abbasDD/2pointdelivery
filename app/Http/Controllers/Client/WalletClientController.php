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
        $statistic['amount_spend'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->sum('amount');

        // unpaid_amount
        $statistic['unpaid_amount'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->where('status', 'pending')->sum('amount');

        // amount_refunded
        $statistic['amount_refunded'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->where('status', 'refunded')->sum('amount');

        // wallets
        $wallets = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->get();

        return view('client.wallet.index', compact('statistic', 'wallets'));
    }
}
