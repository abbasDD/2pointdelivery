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
        $statistic['total_revenue'] =  UserWallet::where('type', 'spend')->where('status', 'success')->sum('amount');
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
        $wallets = UserWallet::where('type', 'spend')->get();

        return view('admin.wallet.received', compact('wallets'));
    }

    // refundRequest
    public function refundRequest()
    {
        // refundRequest
        $wallets = UserWallet::where('type', 'refund')->get();

        return view('admin.wallet.refund', compact('wallets'));
    }

    // approveRefundRequest
    public function approveRefundRequest(Request $request)
    {
        // Validator
        $request->validate([
            'wallet_id' => 'required',
            'transaction_id' => 'required',
        ]);

        $wallet = UserWallet::where('id', $request->wallet_id)->where('status', 'pending')->where('type', 'refund')->first();
        if (!$wallet) {
            return back()->with('error', 'Wallet Transaction not found!');
        }

        $wallet->transaction_id = $request->transaction_id;
        $wallet->status = 'success';
        $wallet->save();

        return back()->with('success', 'Wallet Transaction approved successfully!');
    }

    // rejectRefundRequest
    public function rejectRefundRequest(Request $request)
    {
        // Validator
        $request->validate([
            'wallet_id' => 'required',
        ]);

        $wallet = UserWallet::where('id', $request->wallet_id)->where('status', 'pending')->where('type', 'refund')->first();
        if (!$wallet) {
            return back()->with('error', 'Wallet Transaction not found!');
        }

        $wallet->status = 'failed';
        $wallet->save();

        return back()->with('success', 'Wallet Transaction rejected successfully!');
    }

    // withdrawRequest
    public function withdrawRequest()
    {
        // withdrawRequest
        $wallets = UserWallet::where('type', 'withdraw')->get();

        return view('admin.wallet.withdraw', compact('wallets'));
    }

    // approveWithdrawRequest
    public function approveWithdrawRequest(Request $request)
    {
        // Validator
        $request->validate([
            'wallet_id' => 'required',
            'transaction_id' => 'required',
        ]);

        $wallet = UserWallet::where('id', $request->wallet_id)->where('status', 'pending')->where('type', 'withdraw')->first();
        if (!$wallet) {
            return back()->with('error', 'Wallet Transaction not found!');
        }

        $wallet->transaction_id = $request->transaction_id;
        $wallet->status = 'success';
        $wallet->save();

        return back()->with('success', 'Wallet Transaction approved successfully!');
    }

    // rejectWithdrawRequest
    public function rejectWithdrawRequest(Request $request)
    {
        // Validator
        $request->validate([
            'wallet_id' => 'required',
        ]);

        $wallet = UserWallet::where('id', $request->wallet_id)->where('status', 'pending')->where('type', 'withdraw')->first();
        if (!$wallet) {
            return back()->with('error', 'Wallet Transaction not found!');
        }

        $wallet->status = 'failed';
        $wallet->save();

        return back()->with('success', 'Wallet Transaction rejected successfully!');
    }
}
