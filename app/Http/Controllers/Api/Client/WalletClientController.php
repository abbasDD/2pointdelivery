<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class WalletClientController extends Controller
{
    // wallet index 
    public function index()
    {

        // If token is not valid return error

        if (!auth()->user()) {
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Unauthorized.',
                'errors' => 'Unauthorized',
            ], 401);
        }

        // amount_spend
        $statistic['amount_spend'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->sum('amount');

        // unpaid_amount
        $statistic['unpaid_amount'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->where('status', 'pending')->sum('amount');

        // amount_refunded
        $statistic['amount_refunded'] = UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->where('status', 'refunded')->sum('amount');

        // return json response
        return response()->json([
            'success' => true,
            'message' => 'Client Profile updated successfully',
            'data' => [
                'statistic' => $statistic,
                'wallet' => UserWallet::where('user_id', auth()->user()->id)->where('user_type', 'client')->get(),
            ]
        ], 200);
    }
}
