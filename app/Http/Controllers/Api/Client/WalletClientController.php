<?php

namespace App\Http\Controllers\Api\Client;

use App\Http\Controllers\Controller;
use App\Http\Resources\ClientWalletResource;
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
                'wallet' => ClientWalletResource::collection(UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'client')->get()),
            ]
        ], 200);
    }

    // postWalletRefundRequest
    public function postWalletRefundRequest(Request $request)
    {
        $booking_id = $request->booking_id;

        // CHeck if booking exist with cancelled status
        $booking = Booking::where('id', $booking_id)->where('client_user_id', Auth::user()->id)->where('status', 'cancelled')->first();
        if (!$booking) {
            // return redirect()->back()->with('error', 'Booking not found');
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Booking not found',
                'errors' => 'Booking not found',
            ], 401);
        }

        // Check if request already in pending status
        $refund = UserWallet::where('booking_id', $booking_id)->where('user_id', Auth::user()->id)->where('user_type', 'client')->where('type', 'refund')->where('status', 'pending')->first();
        if ($refund) {
            // return redirect()->back()->with('error', 'Request already pending');
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Request already pending',
                'errors' => 'Request already pending',
            ], 401);
        }

        // Check if exist with success status
        $refund = UserWallet::where('booking_id', $booking_id)->where('user_id', Auth::user()->id)->where('user_type', 'client')->where('type', 'refund')->where('status', 'success')->first();
        if ($refund) {
            // return redirect()->back()->with('error', 'Request already approved');
            return response()->json([
                'success' => false,
                'statusCode' => 401,
                'message' => 'Request already approved',
                'errors' => 'Request already approved',
            ], 401);
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

        // Call notificaion helper to send notification to admin
        app('notificationHelper')->sendNotification($refund->user_id, 1, 'admin', 'wallet', $refund->id, 'Refund Request', 'Refund request has been sent!');

        // return redirect()->back()->with('success', 'Request sent successfully');
        return response()->json([
            'success' => true,
            'message' => 'Request sent successfully',
            'data' => []
        ], 200);
    }
}
