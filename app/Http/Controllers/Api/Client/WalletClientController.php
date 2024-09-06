<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\HelperWalletResource;
use App\Models\Booking;
use App\Models\UserWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class WalletClientController extends Controller
{
    // wallet index 
    public function index()
    {

        // If token is not valid return error

        if (!Auth::user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // amount_spend
        $statistic['amount_spend'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->where('status', 'success')->sum('amount');

        // unpaid_amount
        $statistic['unpaid_amount'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->where('status', 'pending')->sum('amount');

        // Cancelled amount
        $statistic['cancelled_amount'] = Booking::where('client_user_id', Auth::user()->id)->where('status', 'cancelled')->sum('total_price');

        // amount_refunded
        $statistic['amount_refunded'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->where('status', 'refunded')->sum('amount');

        // return json response
        return response()->json([
            'success' => true,
            'message' => 'Client wallet fetched successfully',
            'data' => [
                'statistic' => $statistic,
                'wallet' => UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->get(),
            ]
        ], 200);
    }
}
