<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\Country;
use App\Models\KycDetail;
use App\Models\State;
use App\Models\User;
use Illuminate\Http\Request;

class KycDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('user_id', auth()->user()->id)->get();
        // dd($kycDetails->front_image);
        return view('helper.kycDetails.index', compact('kycDetails'));
    }

    public function create()
    {
        // Get countries
        $countries = Country::all();

        $addressData = [
            'countries' => $countries,
            'selectedStates' => [],
            'selectedCities' => [],
        ];

        // Get already added kycdetails
        $kycDetailTypes = KycDetail::where('user_id', auth()->user()->id)->pluck('id_type')->toArray();
        // dd($kycDetailTypes);

        return view('helper.kycDetails.create', compact('addressData'));
    }

    // Store new kyc

    public function store(Request $request)
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

        // Check if user exist
        $user = User::where('id', auth()->user()->id)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        $kycDetail = KycDetail::create([
            'user_id' => auth()->user()->id,
            'type' => 'helper',
        ]);

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
        return redirect()->route('helper.kyc_details')->with('success', 'KYC details added successfully');
    }



    public function edit(Request $request)
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        // dd($kycDetails->front_image);

        // Get countries
        $countries = Country::all();

        $selectedStates = State::where('country_id', $kycDetails->country)->get();
        $selectedCities = City::where('state_id', $kycDetails->state)->get();

        // Address Data 
        $addressData = [
            'countries' => $countries,
            'selectedStates' => $selectedStates,
            'selectedCities' => $selectedCities,
        ];

        // Get already added kycdetails
        $kycDetailTypes = KycDetail::where('user_id', auth()->user()->id)->where('id_type', '!=', $kycDetails->id_type)->pluck('id_type')->toArray();


        return view('helper.kycDetails.edit', compact('kycDetails', 'addressData'));
    }


    public function show(Request $request)
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('id', $request->id)->where('user_id', auth()->user()->id)->first();
        // dd($kycDetails->front_image);

        // Get countries
        $countries = Country::all();

        return view('helper.kycDetails.show', compact('kycDetails', 'countries'));
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
        $kycDetail = KycDetail::where('user_id', auth()->user()->id)->where('id', $request->id)->first();

        if (!$kycDetail) {
            // Return with error
            return redirect()->back()->with('error', 'KYC details not found');
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
        return redirect()->route('helper.kyc_details')->with('success', 'KYC details updated successfully');

        // dd($kycDetail);
    }
}
