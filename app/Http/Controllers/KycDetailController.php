<?php

namespace App\Http\Controllers;

use App\Models\KycDetail;
use App\Http\Requests\StoreKycDetailRequest;
use App\Http\Requests\UpdateKycDetailRequest;
use Illuminate\Http\Request;

class KycDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('user_id', auth()->user()->id)->first();
        // dd($kycDetails->front_image);
        return view('client.kyc_details', compact('kycDetails'));
    }

    // Update kyc

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'id_type' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'issue_date' => 'required|string|max:255',
            'expiry_date' => 'required|string|max:255',
        ]);

        // Check if kyc exist or not
        $kycDetail = KycDetail::where('user_id', auth()->user()->id)->first();

        if (!$kycDetail) {
            // Insert 
            KycDetail::create([
                'user_id' => auth()->user()->id,
            ]);
            // Get newly created kyc
            $kycDetail = KycDetail::where('user_id', auth()->user()->id)->first();
        }

        // Check if front_image exist or not
        if ($request->hasFile('front_image')) {
            $request->validate([
                'front_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            // upload image
            $file = $request->file('front_image');
            $updatedFilename = time() . '_front' . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/kyc/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $front_image = $updatedFilename;
            $kycDetail->front_image = $updatedFilename;
        }

        // Check if back_image exist or not
        if ($request->hasFile('back_image')) {
            $request->validate([
                'back_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            ]);
            // upload image
            $back_image = $request->file('back_image');
            $updatedFilename = time() . '_back' . '.' . $back_image->getClientOriginalExtension();
            $destinationPath = public_path('images/kyc/');
            $back_image->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $back_image = $updatedFilename;
            $kycDetail->back_image = $updatedFilename;
        }

        // Update kyc
        $kycDetail->id_type = $request->id_type;
        $kycDetail->id_number = $request->id_number;
        $kycDetail->country = $request->country;
        $kycDetail->state = $request->state;
        $kycDetail->city = $request->city;
        $kycDetail->issue_date = $request->issue_date;
        $kycDetail->expiry_date = $request->expiry_date;

        $kycDetail->save();

        // Redirect to kyc details page
        return redirect()->back()->with('success', 'KYC details updated successfully');

        // dd($kycDetail);
    }
}
