<?php

namespace App\Http\Controllers;

use App\Models\KycDetail;
use App\Http\Requests\StoreKycDetailRequest;
use App\Http\Requests\UpdateKycDetailRequest;
use App\Http\Resources\KycDetailResource;
use App\Models\City;
use App\Models\Country;
use App\Models\KycType;
use App\Models\State;
use App\Models\User;
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
        $kycDetails = KycDetail::where('user_id', Auth::user()->id)->get();
        // dd($kycDetails);
        return view('client.kycDetails.index', compact('kycDetails'));
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

        // Check if kyc_types exist
        $kycTypes = KycType::all();
        if ($kycTypes->count() == 0) {
            return redirect()->back()->with('error', 'KYC types not found');
        }

        // Get already added Kyc Types
        $existedKycTypes = KycDetail::where('user_id', Auth::user()->id)->get();
        if ($existedKycTypes) {
            $existedKycTypes = $existedKycTypes->pluck('kyc_type_id')->toArray();
        }


        // Get KYC Types not present in the existedKycTypes array
        $kycTypes = KycType::whereNotIn('id', $existedKycTypes)->get();

        return view('client.kycDetails.create', compact('addressData', 'existedKycTypes', 'kycTypes'));
    }

    // Store new kyc

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'kyc_type_id' => 'required|exists:kyc_types,id',
            'id_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            // 'issue_date' => 'required|date|',
            // 'expiry_date' => 'required|string|max:255',
            'issue_date' => 'required|date|before:expiry_date',
            'expiry_date' => 'required|date|after:issued_date',
        ]);

        // Check if user exist
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return redirect()->back()->with('error', 'User not found');
        }

        // Check if kyc exist or not
        $kycDetail = KycDetail::where('user_id', Auth::user()->id)->where('kyc_type_id', $request->kyc_type_id)->first();

        if ($kycDetail) {
            return redirect()->back()->with('error', 'You have already added this KYC');
        }

        $kycDetail = KycDetail::create([
            'user_id' => Auth::user()->id,
            'type' => 'client',
            'kyc_type_id' => $request->kyc_type_id,
        ]);

        // Check if front_image exist or not
        if ($request->hasFile('front_image')) {
            $request->validate([
                'front_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
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
                'back_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
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

        // Convert date to date format for DB storage - YYYY-MM-DD

        // Update kyc
        $kycDetail->kyc_type_id = $request->kyc_type_id;
        $kycDetail->id_number = $request->id_number;
        $kycDetail->country = $request->country;
        $kycDetail->state = $request->state;
        $kycDetail->city = $request->city;
        $kycDetail->issue_date = date('Y-m-d', strtotime($request->issue_date));
        $kycDetail->expiry_date = date('Y-m-d', strtotime($request->expiry_date));

        $kycDetail->save();

        // Send Notification to Admin
        UserNotification::create([
            'sender_user_id' => Auth::user()->id,
            'receiver_user_id' => 1,
            'receiver_user_type' => 'admin',
            'reference_id' => $kycDetail->id,
            'type' => 'kyc_detail',
            'title' => 'KYC details added',
            'content' => 'KYC is submitted by ' . Auth::user()->name . ' for ' . $kycDetail->kycType->name . ' KYC',
            'read' => 0
        ]);

        // Redirect to kyc details page
        return redirect()->route('client.kyc_details')->with('success', 'KYC details added successfully');
    }



    public function edit(Request $request)
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
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

        // Get KYC Type
        $kycTypes = KycType::where('id', $kycDetails->kyc_type_id)->first();

        // Get already added Kyc Types
        $ExistedKycTypes = KycDetail::where('user_id', Auth::user()->id)->where('kyc_type_id', '!=', $kycDetails->kyc_type_id)->pluck('kyc_type_id')->toArray();

        return view('client.kycDetails.edit', compact('kycDetails', 'addressData', 'ExistedKycTypes', 'kycTypes'));
    }


    public function show(Request $request)
    {
        // Get kyc details of logged in user
        $kycDetails = KycDetail::where('id', $request->id)->where('user_id', Auth::user()->id)->first();
        // dd($kycDetails->front_image);

        // Get countries
        $countries = Country::all();

        return view('client.kycDetails.show', compact('kycDetails', 'countries'));
    }

    // Update kyc

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            // 'kyc_type_id' => 'required|string|max:255',
            'id_number' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            // 'issue_date' => 'required|string|max:255',
            // 'expiry_date' => 'required|string|max:255',
            'issue_date' => 'required|date|before:expiry_date',
            'expiry_date' => 'required|date|after:issued_date',
        ]);

        // Check if kyc exist or not
        $kycDetail = KycDetail::where('user_id', Auth::user()->id)->where('id', $request->id)->first();

        if (!$kycDetail) {
            // Return with error
            return redirect()->back()->with('error', 'KYC details not found');
        }

        // Check if front_image exist or not
        if ($request->hasFile('front_image')) {
            $request->validate([
                'front_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
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
                'back_image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,heic,heif',
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
        // $kycDetail->kyc_type_id = $request->kyc_type_id;
        $kycDetail->id_number = $request->id_number;
        $kycDetail->country = $request->country;
        $kycDetail->state = $request->state;
        $kycDetail->city = $request->city;
        $kycDetail->issue_date = date('Y-m-d', strtotime($request->issue_date));
        $kycDetail->expiry_date = date('Y-m-d', strtotime($request->expiry_date));
        $kycDetail->is_verified = 0;

        $kycDetail->save();

        // Send Notification to Admin
        UserNotification::create([
            'sender_user_id' => Auth::user()->id,
            'receiver_user_id' => 1,
            'receiver_user_type' => 'admin',
            'reference_id' => $kycDetail->id,
            'type' => 'kyc_detail',
            'title' => 'KYC details updated',
            'content' => 'KYC is updated by ' . Auth::user()->name . ' for ' . $kycDetail->kycType->name . ' KYC',
            'read' => 0
        ]);

        // Redirect to kyc details page
        return redirect()->route('client.kyc_details')->with('success', 'KYC details updated successfully');
    }
}
