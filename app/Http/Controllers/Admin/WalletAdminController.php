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
}
