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
        // Get revenue
        $statistic['total_earnings'] = 100;

        // Unpaid Driver Earning
        $statistic['total_payments'] = 110;

        // Total Earning
        $statistic['total_taxes'] = 10;

        // total_revenue
        $statistic['total_revenue'] = 120;

        // wallets
        $wallets = UserWallet::get();


        return view('admin.wallet.index', compact('statistic', 'wallets'));
    }
}
