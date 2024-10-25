<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Controllers\FcmController;
use App\Models\Client;
use App\Models\Country;
use App\Models\KycDetail;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class KycDetailController extends Controller
{

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::select('kyc_details.*', 'users.email as user_email')
            ->join('users', 'users.id', '=', 'kyc_details.user_id')
            // ->where('is_verified', 0)
            ->with('kycType')
            ->orderBy('kyc_details.user_id')
            ->get();

        // dd($kycDetails->front_image);
        return view('admin.kycDetails.index', compact('kycDetails'));
    }

    public function show(Request $request)
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::select('kyc_details.*', 'kyc_types.name as kyc_type_name', 'users.email as user_email')
            ->join('users', 'users.id', '=', 'kyc_details.user_id')
            ->join('kyc_types', 'kyc_types.id', '=', 'kyc_details.kyc_type_id')
            ->where('kyc_details.id', $request->id)->first();
        // dd($kycDetails->front_image);

        $userDetails = null;

        // Get Client details if type is client
        if ($kycDetails->type == 'client') {
            $userDetails = Client::where('user_id', $kycDetails->user_id)->first();
        }

        // Get Helper details if type is helper
        if ($kycDetails->type == 'helper') {
            $userDetails = Client::where('user_id', $kycDetails->user_id)->first();
        }

        // Get countries
        $countries = Country::all();

        return view('admin.kycDetails.show', compact('kycDetails', 'countries', 'userDetails'));
    }

    public function approveKycDetail(Request $request)
    {
        $kycDetail = KycDetail::find($request->id);
        if (!$kycDetail) {
            return redirect()->back()->with('error', 'KYC details not found');
        }

        $kycDetail->is_verified = 1;
        $kycDetail->save();

        // Call notificaion helper to send notification
        app('notificationHelper')->sendNotification(Auth::user()->id, $kycDetail->user_id, $kycDetail->type, 'kyc_detail', $kycDetail->id, 'KYC details approved', 'Your KYC details has been approved');

        return redirect()->back()->with('success', 'KYC details approved successfully');
    }

    public function rejectKycDetail(Request $request)
    {
        $kycDetail = KycDetail::find($request->id);
        if (!$kycDetail) {
            return redirect()->back()->with('error', 'KYC details not found');
        }

        $kycDetail->is_verified = 2;
        $kycDetail->save();

        // Call notificaion helper to send notification
        app('notificationHelper')->sendNotification(Auth::user()->id, $kycDetail->user_id, $kycDetail->type, 'kyc_detail', $kycDetail->id, 'KYC details rejected', 'Your KYC details has been rejected');

        return redirect()->back()->with('success', 'KYC details rejected successfully');
    }
}
