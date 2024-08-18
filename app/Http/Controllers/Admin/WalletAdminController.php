<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletAdminController extends Controller
{
    //index
    public function index()
    {
        // Get available balance
        $balance['available'] = 100;

        // Total Earning
        $balance['total'] = 110;

        // WithdrawnAmount
        $balance['withdrawn'] = 10;

        return view('admin.wallet.index', compact('balance'));
    }
}
