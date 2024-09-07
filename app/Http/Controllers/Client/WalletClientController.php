<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletClientController extends Controller
{
    // wallet index 
    public function index()
    {
        // amount_spend
        $statistic['amount_spend'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->where('status', 'success')->sum('amount');

        // unpaid_amount
        $statistic['unpaid_amount'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->where('status', 'pending')->sum('amount');

        // amount_refunded
        $statistic['amount_refunded'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->where('status', 'refunded')->sum('amount');

        // cancelled_amount
        $statistic['cancelled_amount'] = Booking::where('client_user_id', Auth::user()->id)->where('status', 'cancelled')->sum('total_price');

        // wallets
        $wallets = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->get();

        return view('client.wallet.index', compact('statistic', 'wallets'));
    }

    // refundRequest
    public function refundRequest($booking_id)
    {
        // CHeck if booking exist with cancelled status
        $booking = Booking::where('id', $booking_id)->where('client_user_id', Auth::user()->id)->where('status', 'cancelled')->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Check if request already in pending status
        $refund = UserWallet::where('booking_id', $booking_id)->where('user_id', Auth::user()->id)->where('user_type', 'client')->where('type', 'refund')->where('status', 'pending')->first();
        if ($refund) {
            return redirect()->back()->with('error', 'Request already pending');
        }

        // Check if exist with success status
        $refund = UserWallet::where('booking_id', $booking_id)->where('user_id', Auth::user()->id)->where('user_type', 'client')->where('type', 'refund')->where('status', 'success')->first();
        if ($refund) {
            return redirect()->back()->with('error', 'Request already approved');
        }


        // Create Refund Request
        $refund = new UserWallet();
        $refund->booking_id = $booking_id;
        $refund->user_id = Auth::user()->id;
        $refund->user_type = 'client';
        $refund->type = 'refund';
        $refund->amount = $booking->total_price;
        $refund->status = 'pending';
        $refund->save();

        return redirect()->back()->with('success', 'Request sent successfully');
    }
}
