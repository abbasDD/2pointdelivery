<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHelperRequest;
use App\Http\Requests\UpdateHelperRequest;
use App\Models\Booking;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\Industry;
use App\Models\ServiceType;
use App\Models\SocialLink;
use App\Models\State;
use App\Models\User;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class HelperController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application complete profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function complete_profile()
    {
        return view('helper.auth.complete_profile');
    }

    /**
     * Show the application complete profile.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function update_profile()
    {
        dd('update profile');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $helper_id = 0;
        // Get helper_id from Helper
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        // No Helper found for this user_id
        if (!$helper) {
            // Create a new Helper
            $helper = new Helper();
            $helper->user_id = auth()->user()->id;
            $helper->save();
            $helper_id = $helper->id;
        }

        $helper_id = $helper->id;

        // Statistics
        $satistics = [
            'total_bookings' => Booking::where('helper_user_id', auth()->user()->id)->count(),
            'accepted_bookings' => Booking::where('helper_user_id', auth()->user()->id)->where('status', 'accepted')->count(),
            'cancelled_bookings' => Booking::where('helper_user_id', auth()->user()->id)->where('status', 'cancelled')->count(),
            'total_earnings' => Booking::where('helper_user_id', auth()->user()->id)->where('status', 'draft')->count(),
        ];

        $bookings = Booking::select('bookings.*', 'booking_deliveries.helper_fee')
            ->where('status', 'pending')
            ->join('booking_deliveries', 'bookings.id', '=', 'booking_deliveries.booking_id')
            ->with('helper')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')
            ->take(10)->get();

        // Booking Client Detail
        // $bookingClient = Client::where('user_id', auth()->user()->id)->first();

        // Check if helper completed its profile
        $helperUpdated = true;

        // Check if personal detail completed
        if ($helper->first_name == null || $helper->last_name == null) {
            $helperUpdated = false;
        }

        // Check if address detail completed
        if ($helper->city == null || $helper->state == null || $helper->country == null) {
            $helperUpdated = false;
        }

        // Check if vehicle detail completed
        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if (!$helperVehicle) {
            $helperUpdated = false;
        }

        // Check if profile is company profile
        if ($helper->company_enabled == 1) {
            // Check if company detail completed
            $companyData = HelperCompany::where('user_id', auth()->user()->id)->first();

            if (!$companyData) {
                $helperUpdated = false;
            }

            // Check if company detail completed

            if ($companyData->company_alias == null || $companyData->city == null) {
                $helperUpdated = false;
            }
        }
        // $helperUpdated = false;

        return view('helper.index', compact('helper', 'bookings', 'satistics', 'helperUpdated'));
    }


    public function kyc_details()
    {
        return view('helper.kyc_details');
    }

    public function bookings()
    {

        $bookings = Booking::where('helper_user_id', auth()->user()->id)
            ->with('helper')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')->get();

        return view('helper.bookings.index', compact('bookings'));
    }

    public function acceptBooking(Request $request)
    {
        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'pending') {
            return redirect()->back()->with('error', 'Booking already accepted');
        }

        $booking->status = 'accepted';
        $booking->helper_id = auth()->user()->id;
        $booking->save();

        return redirect()->back()->with('success', 'Booking accepted successfully!');
    }


    public function settings()
    {
        return view('helper.settings');
    }


    // Request Copmany Profile
    public function requestCompany(Request $request)
    {
        // Check if user exist
        $helper = Helper::where('user_id', auth()->user()->id)->first();
        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Check if helper company already exist  
        $helperCompany = HelperCompany::where('user_id', auth()->user()->id)->first();
        if ($helperCompany) {
            // update the company_enabled to 1
            $helper->company_enabled = 1;
            $helper->save();

            return redirect()->back()->with('success', 'Company profile requested successfully');
        }

        // Create a new helper company
        $helperCompany = new HelperCompany();
        $helperCompany->user_id = auth()->user()->id;
        $helperCompany->helper_id = $helper->id;
        $helperCompany->company_alias = $request->company_alias;
        $helperCompany->legal_name = $request->legal_name;
        $helperCompany->save();

        return redirect()->back()->with('success', 'Company profile requested successfully');
    }

    public function edit_profile()
    {
        // Get helper data
        $helperData = Helper::where('user_id', auth()->user()->id)->with('service_types')->first();
        // dd($helperData);
        // Create a new helper if not found
        if (!$helperData) {
            $helperData = new Helper();
            $helperData->user_id = auth()->user()->id;
            $helperData->save();

            $helperData = Helper::where('user_id', auth()->user()->id)->first();
        }

        // Get all social links
        $socialLinks = SocialLink::where('user_id', auth()->user()->id)->where('user_type', 'helper')->get();

        $social_links = [];

        // Loop through social links
        foreach ($socialLinks as $socialLink) {
            $social_links[$socialLink->key] = $socialLink->link;
        }

        // dd($social_links);

        // Get helper company data
        $helperCompanyData = HelperCompany::where('user_id', auth()->user()->id)->first();
        if (!$helperCompanyData) {
            $helperCompanyData = new HelperCompany();
            $helperCompanyData->user_id = auth()->user()->id;
            $helperCompanyData->helper_id = $helperData->id;
            $helperCompanyData->save();

            $helperCompanyData = HelperCompany::where('user_id', auth()->user()->id)->first();
        }

        // dd($helperCompanyData);

        // Get list of all countries
        $countries = Country::all();


        $companyStates = State::where('country_id', $helperCompanyData->country)->get();

        $companyCities = City::where('state_id', $helperCompanyData->state)->get();

        // Client Address Detail

        $helperStates = State::where('country_id', $helperData->country)->get();
        $helperCities = City::where('state_id', $helperData->state)->get();

        // Address Data 
        $addressData = [
            'countries' => $countries,
            'helperStates' => $helperStates,
            'helperCities' => $helperCities,
            'companyStates' => $companyStates,
            'companyCities' => $companyCities
        ];

        // All Vehicle Types 
        $vehicleTypes = VehicleType::where('is_active', 1)->get();

        // Get vehicle data of helper
        $vehicleData = HelperVehicle::where('helper_id', $helperData->id)->first();
        // dd($vehicleData);


        // Get service types
        $services = ServiceType::where('is_active', 1)->get();
        // dd($services);


        // Get list of industries
        $industries = Industry::all();

        return view('helper.profile.edit', compact('helperData', 'social_links', 'helperCompanyData', 'addressData', 'vehicleTypes', 'vehicleData', 'services', 'industries'));
    }


    // Route to update personal profile data
    public function personalInfo(Request $request)
    {
        // valideate request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'gender' => 'required|string|max:255',
            'date_of_birth' => 'required|string|max:255',
            'phone_no' => 'required|string|max:255',
            'company_enabled' => 'required|integer',
            'services' => 'required|array',
        ]);

        // Check if user exist
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Set default profile image to null
        $profile_image = $helper->profile_image ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('profile_image')) {
            $file = $request->file('profile_image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/users/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $profile_image = $updatedFilename;
        }

        // Update the helper data
        $helper->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'phone_no' => $request->phone_no,
            'date_of_birth' => $request->date_of_birth,
            'tax_id' => $request->tax_id,
            'profile_image' => $profile_image,
            'company_enabled' => $request->company_enabled
        ]);
        // dd($request->all());


        // Sync services for the vehicle type
        $helper->service_types()->sync($request->services);

        return redirect()->back()->with('success', 'Profile info updated successfully!');
    }

    // Route to update address data
    public function addressInfo(Request $request)
    {
        // valideate request
        $request->validate([
            'suite' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
        ]);

        // Check if user exist
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Update the helper data
        $helper->update($request->all());
        // dd($request->all());

        return redirect()->back()->with('success', 'Profile Address updated successfully!');
    }

    // Route to update vehicle profile data
    public function vehicleInfo(Request $request)
    {
        // valideate request
        $request->validate([
            'vehicle_type_id' => 'required|integer',
            'vehicle_number' => 'required|string|max:255',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:255',
            'vehicle_year' => 'required|string|max:255',
        ]);


        // Check if user exist
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Check if Helper Vehicle already exist
        $helperVehicle = HelperVehicle::where('helper_id', $helper->id)->first();

        if ($helperVehicle) {
            $helperVehicle->update($request->all());
        } else {
            $helperVehicle = new HelperVehicle();
            $helperVehicle->user_id = auth()->user()->id;
            $helperVehicle->helper_id = $helper->id;
            $helperVehicle->vehicle_type_id = $request->vehicle_type_id;
            $helperVehicle->vehicle_number = $request->vehicle_number;
            $helperVehicle->vehicle_make = $request->vehicle_make;
            $helperVehicle->vehicle_model = $request->vehicle_model;
            $helperVehicle->vehicle_color = $request->vehicle_color;
            $helperVehicle->vehicle_year = $request->vehicle_year;
            $helperVehicle->save();
        }


        return redirect()->back()->with('success', 'Vehicle info updated successfully!');
    }

    // Route to update address data
    public function socialInfo(Request $request)
    {

        // Check if user exist
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        foreach ($request->all() as $key => $link) {
            $socialLink = SocialLink::where('key', $key)
                ->where('user_id', auth()->user()->id)
                ->where('user_type', 'helper')
                ->first();
            if ($socialLink) {
                $socialLink->link = $link;
                $socialLink->save();
            } else {
                $socialLink = new SocialLink();
                $socialLink->user_id = auth()->user()->id;
                $socialLink->user_type = 'helper';
                $socialLink->key = $key;
                $socialLink->link = $link;
                $socialLink->save();
            }
        }

        return redirect()->back()->with('success', 'Profile Social updated successfully!');
    }


    public function companyInfo(Request $request)
    {
        // valideate request
        $request->validate([
            'company_alias' => 'required|string|max:255',
            'legal_name' => 'required|string|max:255',
            'industry' => 'required|string|max:255',
            'email' => 'required|string|max:255',
            'business_phone' => 'required|string|max:255',
            'suite' => 'required|string|max:255',
            'street' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'country' => 'required|string|max:255',
            'zip_code' => 'required|string|max:255',
        ]);

        // Check if user exist
        $helperCompany = HelperCompany::where('user_id', auth()->user()->id)->first();

        if (!$helperCompany) {
            return redirect()->back()->with('error', 'Helper Company not found');
        } // Set default profile image to null
        $company_logo = $helperCompany->company_logo ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('company_logo')) {
            $file = $request->file('company_logo');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/company/');
            $file->move($destinationPath, $updatedFilename);

            // Set the company_logo attribute to the new file name
            $company_logo = $updatedFilename;
        }

        // Remove company_logo as file from request
        $request->request->remove('company_logo');

        // Update the helper data with company_logo included
        $helperCompany->update(array_merge($request->all(), ['company_logo' => $company_logo]));

        // dd($request->all());

        return redirect()->back()->with('success', 'Company Profile info updated successfully!');
    }

    // Route to update password data
    public function passwordInfo(Request $request)
    {
        // valideate request
        $request->validate([
            'current_password' => 'required|string|max:255',
            'new_password' => 'required|string|max:255',
            'confirm_password' => 'required|string|max:255',
        ]);

        if (!Hash::check($request->current_password, auth()->user()->password)) {
            return redirect()->back()->with('error', 'Current password does not match');
        }

        if ($request->new_password != $request->confirm_password) {
            return redirect()->back()->with('error', 'Password does not match');
        }

        // Check if user exist
        $user = User::where('id', auth()->user()->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Update passowrd
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Password updated successfully!');
    }


    public function teams()
    {
        return view('helper.teams');
    }

    public function track_order()
    {
        return view('helper.track_order');
    }


    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        $currentUserId = auth()->id(); // Get the ID of the current authenticated user

        // Get list of all admins, excluding the current user
        $admins = DB::table('admins')
            ->select('user_id', 'first_name', 'last_name')
            ->where('user_id', '!=', $currentUserId) // Exclude current user
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });

        // Get list of all clients, excluding the current user
        $clients = DB::table('clients')
            ->select('user_id', 'first_name', 'last_name')
            ->where('user_id', '!=', $currentUserId) // Exclude current user
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });

        // Get list of all helpers, excluding the current user
        $helpers = DB::table('helpers')
            ->select('user_id', 'first_name', 'last_name')
            ->where('user_id', '!=', $currentUserId) // Exclude current user
            ->where(function ($query) use ($search) {
                $query->where('first_name', 'like', "%{$search}%")
                    ->orWhere('last_name', 'like', "%{$search}%");
            });

        // Union all query results
        $users = $clients->union($helpers)->union($admins)->get();

        return response()->json($users);
    }
}
