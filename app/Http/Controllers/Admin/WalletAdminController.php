<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\UserWallet;
use Illuminate\Http\Request;

class WalletAdminController extends Controller
{
    //index
    public function index()
    {
        // Get Userwallet Statistics
        $statistic['total_revenue'] =  UserWallet::where('type', 'received')->where('status', 'success')->sum('amount');
        $statistic['total_payments'] = UserWallet::whereIn('type', ['refund', 'withdraw'])->where('status', 'success')->sum('amount');
        $statistic['total_taxes'] = 0;
        $statistic['total_earnings'] = $statistic['total_revenue'] - $statistic['total_payments'] - $statistic['total_taxes'];

        // wallets
        $wallets = UserWallet::latest()->paginate(10);


        return view('admin.wallet.index', compact('statistic', 'wallets'));
    }

    // receivedTransaction
    public function receivedTransaction()
    {
        // refundRequest
        $wallets = UserWallet::where('type', 'received')->get();

        return view('admin.wallet.received', compact('wallets'));
    }

    // refundRequest
    public function refundRequest()
    {
        // refundRequest
        $wallets = UserWallet::where('type', 'refund')->get();

        return view('admin.wallet.refund', compact('wallets'));
    }

    // withdrawRequest
    public function withdrawRequest()
    {
        // withdrawRequest
        $wallets = UserWallet::where('type', 'withdraw')->get();

        return view('admin.wallet.withdraw', compact('wallets'));
    }
}
