<?php

namespace App\Http\Controllers\Client;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class WalletClientController extends Controller
{
    // wallet index
    public function index()
    {
        // Get available balance
        $balance['available'] = 100;

        // Total Earning
        $balance['total'] = 110;

        // WithdrawnAmount
        $balance['withdrawn'] = 10;

        return view('client.wallet.index', compact('balance'));
    }
}
