<?php

namespace App\Http\Controllers\Api\Helper;

use App\Http\Controllers\Controller;
use App\Http\Resources\HelperWalletResource;
use App\Models\HelperBankAccount;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Auth;

class WalletHelperController extends Controller
{
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


        // Total Earning
        $statistic['total'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'earned')->where('status', 'success')->sum('amount');

        // WithdrawnAmount
        $statistic['withdrawn'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'withdraw')->where('status', 'success')->sum('amount');

        // With request pending
        $pendingRequestAmount = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'withdraw')->where('status', 'pending')->sum('amount');

        // Get available balance
        $statistic['available'] = $statistic['total'] - $statistic['withdrawn'] - $pendingRequestAmount;

        // Get helper bank accounts
        $helperBankAccounts = HelperBankAccount::where('user_id', Auth::user()->id)->get();

        // return json response
        return response()->json([
            'success' => true,
            'message' => 'Helper wallet fetched successfully',
            'data' => [
                'statistic' => $statistic,
                'wallet' => HelperWalletResource::collection(UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->get()),
                'helperBankAccounts' => $helperBankAccounts
            ]
        ], 200);
    }
}
