<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycDetail;
use Illuminate\Http\Request;

class KycDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('is_verified', 0)->get();

        // dd($kycDetails->front_image);
        return view('admin.kycDetails.index', compact('kycDetails'));
    }

    public function approveKycDetail(Request $request)
    {
        $kycDetail = KycDetail::find($request->id);
        if (!$kycDetail) {
            return redirect()->back()->with('error', 'KYC details not found');
        }

        $kycDetail->is_verified = 1;
        $kycDetail->save();

        return redirect()->back()->with('success', 'KYC details approved successfully');
    }
}
