<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHelperRequest;
use App\Http\Requests\UpdateHelperRequest;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\City;
use App\Models\Client;
use App\Models\Country;
use App\Models\HelperBankAccount;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\Industry;
use App\Models\KycDetail;
use App\Models\ServiceType;
use App\Models\SocialLink;
use App\Models\State;
use App\Models\User;
use App\Models\UserWallet;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;

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
    public function switchToClient()
    {
        // Get user data
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return redirect()->route('index')->with('error', 'User not found');
        }


        // Get Client data from DB
        $client = Client::where('user_id', Auth::user()->id)->first();

        // If client not found
        if (!$client) {
            // Check if Helper is created with same id
            $helper = Helper::where('user_id', Auth::user()->id)->first();

            // If helper is found then duplicate data to client
            if ($helper) {
                // Check if helper first name and last name is not null
                if ($helper->first_name == null || $helper->last_name == null) {
                    return redirect()->route('helper.profile')->with('error', 'Please fill your helper detail first');
                }

                $client = Client::create([
                    'user_id' => Auth::user()->id,
                    'company_enabled' => 0,
                    'first_name' => $helper->first_name ?? '',
                    'middle_name' => $helper->middle_name ?? '',
                    'last_name' => $helper->last_name ?? '',
                    'gender' => $helper->gender ?? '',
                    'date_of_birth' => $helper->date_of_birth ?? '',
                    'profile_image' => $helper->profile_image ?? '',
                    'thumbnail' => $helper->thumbnail ?? '',
                    'phone_no' => $helper->phone_no ?? '',
                    'suite' => $helper->suite ?? '',
                    'street' => $helper->street ?? '',
                    'city' => $helper->city     ?? '',
                    'state' => $helper->state ?? '',
                    'country' => $helper->country ?? '',
                    'zip_code' => $helper->zip_code ?? '',
                ]);
            }
            // If not then create a simple client
            else {
                $client = Client::create([
                    'user_id' => Auth::user()->id,
                ]);
            }
        }


        // Store login_type to Session
        session(['login_type' => 'client']);

        // Get client first name and last name
        $clientInfo = Client::where('user_id', Auth::user()->id)->first();
        if ($clientInfo) {
            session(['full_name' => $clientInfo->first_name . ' ' . $clientInfo->last_name]);
            // set profile_image
            session(['thumbnail' => asset('images/users/thumbnail/' . $clientInfo->thumbnail)]);
        }

        $user->client_enabled = 1;
        $user->save();

        // Redirect to dashboard
        return redirect()->route('client.index')->with('success', 'Switched to Client Dashboard');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // Change session variable login_type to client
        session(['login_type' => 'helper']);

        // dd(session('login_type'));

        $helper_id = 0;
        // Get helper_id from Helper
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        // No Helper found for this user_id
        if (!$helper) {
            // Create a new Helper
            $helper = new Helper();
            $helper->user_id = Auth::user()->id;
            $helper->save();
            $helper_id = $helper->id;
        }

        $helper_id = $helper->id;

        // Calculate helper earnings
        $helper_earnings = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'earned')->sum('amount');

        // Statistics
        $satistics = [
            'total_bookings' => Booking::where('helper_user_id', Auth::user()->id)->count(),
            'accepted_bookings' => Booking::where('helper_user_id', Auth::user()->id)->where('status', 'accepted')->count(),
            'cancelled_bookings' => Booking::where('helper_user_id', Auth::user()->id)->where('status', 'cancelled')->count(),
            'total_earnings' => $helper_earnings,
        ];

        $bookings = [];

        // Check if helper is_approved is 1
        // if ($helper->is_approved == 1) {

        // Get helperServices list
        $helperServices = $helper->service_types();

        // pluck the service type ids
        $helperServiceIds = $helperServices->pluck('id')->toArray();
        // dd($helperServiceIds);

        $bookings = Booking::with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->where('status', 'pending')
            ->whereIn('booking_type', ['delivery', 'moving'])
            ->where('client_user_id', '!=', Auth::user()->id)
            ->whereIn('service_type_id', $helperServiceIds)
            ->orderBy('bookings.updated_at', 'desc')->get();

        // dd($bookings);

        foreach ($bookings as $booking) {
            if ($booking->helper_user_id != NULL) {
                $booking->helper = Helper::where('user_id', $booking->helper_user_id)->first();
            }

            $booking->client = Client::where('user_id', $booking->client_user_id)->first();

            $booking->payment = null;

            if ($booking->booking_type == 'delivery') {
                $booking->payment = BookingDelivery::where('booking_id', $booking->id)->first();
            }

            if ($booking->booking_type == 'moving') {
                $booking->payment = BookingMoving::where('booking_id', $booking->id)->first();
            }
        }
        // }

        // Booking Client Detail
        // $bookingClient = Client::where('user_id', Auth::user()->id)->first();

        // Check if helper completed its profile
        $helperProfileUpdated = true;

        // Check if personal detail completed
        if ($helper->first_name == null || $helper->last_name == null) {
            $helperProfileUpdated = false;
        }

        // Check if address detail completed
        if ($helper->city == null || $helper->state == null || $helper->country == null) {
            $helperProfileUpdated = false;
        }


        // Check if profile is company profile
        if ($helper->company_enabled == 1) {
            // Check if company detail completed
            $companyData = HelperCompany::where('user_id', Auth::user()->id)->first();

            if (!$companyData) {
                $helperUpdated = false;
            }
            // Check if company detail completed
            else {
                if ($companyData->company_alias == null || $companyData->city == null) {
                    $helperUpdated = false;
                }
            }
        }

        $helperVehicleUpdated = true;
        // Check if vehicle detail completed
        $helperVehicle = HelperVehicle::where('user_id', Auth::user()->id)->first();
        if (!$helperVehicle) {
            $helperVehicleUpdated = false;
        }
        // $helperUpdated = false;

        $helperKycUpdated = true;
        $helperKyc = KycDetail::where('user_id', Auth::user()->id)->first();
        if (!$helperKyc) {
            $helperKycUpdated = false;
        }

        return view('helper.index', compact('helper', 'helperVehicle', 'helperKyc', 'bookings', 'satistics', 'helperProfileUpdated', 'helperVehicleUpdated', 'helperKycUpdated'));
    }


    public function kyc_details()
    {
        return view('helper.kyc_details');
    }

    public function bookings()
    {

        $bookings = Booking::where('helper_user_id', Auth::user()->id)
            ->with('helper')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.updated_at', 'desc')->get();

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
        $booking->helper_id = Auth::user()->id;
        $booking->save();

        return redirect()->back()->with('success', 'Booking accepted successfully!');
    }


    // Request Copmany Profile
    public function requestCompany(Request $request)
    {
        // Check if user exist
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Check if helper company already exist  
        $helperCompany = HelperCompany::where('user_id', Auth::user()->id)->first();
        if ($helperCompany) {
            // update the company_enabled to 1
            $helper->company_enabled = 1;
            $helper->save();

            return redirect()->back()->with('success', 'Company profile requested successfully');
        }

        // Create a new helper company
        $helperCompany = new HelperCompany();
        $helperCompany->user_id = Auth::user()->id;
        $helperCompany->helper_id = $helper->id;
        $helperCompany->company_alias = $request->company_alias;
        $helperCompany->legal_name = $request->legal_name;
        $helperCompany->save();

        return redirect()->back()->with('success', 'Company profile requested successfully');
    }

    public function edit_profile()
    {
        // Get helper data
        $helperData = Helper::where('user_id', Auth::user()->id)->with('service_types')->first();
        // dd($helperData);
        // Create a new helper if not found
        if (!$helperData) {
            $helperData = new Helper();
            $helperData->user_id = Auth::user()->id;
            $helperData->save();

            $helperData = Helper::where('user_id', Auth::user()->id)->first();
        }

        // Get helperServices list
        $helperServices = $helperData->service_types();

        // pluck the service type ids
        $helperServiceIds = $helperServices->pluck('id')->toArray();
        // dd($helperServiceIds);

        // Get all social links
        $socialLinks = SocialLink::where('user_id', Auth::user()->id)->where('user_type', 'helper')->get();

        $social_links = [];

        // Loop through social links
        foreach ($socialLinks as $socialLink) {
            $social_links[$socialLink->key] = $socialLink->link;
        }

        // dd($social_links);

        // Get helper company data
        $helperCompanyData = HelperCompany::where('user_id', Auth::user()->id)->first();
        if (!$helperCompanyData) {
            $helperCompanyData = new HelperCompany();
            $helperCompanyData->user_id = Auth::user()->id;
            $helperCompanyData->helper_id = $helperData->id;
            $helperCompanyData->save();

            $helperCompanyData = HelperCompany::where('user_id', Auth::user()->id)->first();
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

        return view('helper.profile.edit', compact('helperData', 'social_links', 'helperCompanyData', 'addressData', 'vehicleTypes', 'vehicleData', 'services', 'industries', 'helperServiceIds'));
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
        // dd($request->all());

        // Check if user exist
        $helper = Helper::where('user_id', Auth::user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }


        // Set default profile image to null
        $profile_image = $helper->profile_image ?? null;
        $thumbnail = $helper->thumbnail ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('profile_image')) {
            $image = Image::read($request->file('profile_image'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('profile_image')->getClientOriginalExtension();
            $destinationPath = public_path('images/users/');
            $image->save($destinationPath . $imageName);

            // Generate Thumbnail Image Upload on Folder Code
            $destinationPathThumbnail = public_path('images/users/thumbnail/');
            $image->resize(100, 100);
            $image->save($destinationPathThumbnail . $imageName);

            $profile_image = $imageName;
            $thumbnail = $imageName;
        }

        // Update the helper data
        $helper->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'phone_no' => $request->phone_no,
            'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
            'service_badge_id' => $request->service_badge_id,
            'profile_image' => $profile_image,
            'thumbnail' => $thumbnail,
            'company_enabled' => $request->company_enabled
        ]);
        // dd($request->all());


        // Sync services for the vehicle type
        $helper->service_types()->sync($request->services);

        // Update session data as well for name

        session(['full_name' => $helper->first_name . ' ' . $helper->last_name]);
        // set profile_image
        if ($helper->thumbnail) {
            session(['thumbnail' => asset('images/users/thumbnail/' . $helper->thumbnail)]);
        }


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
        $helper = Helper::where('user_id', Auth::user()->id)->first();

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
            'vehicle_type_id' => 'required|string|max:255',
            'vehicle_number' => 'required|string|max:255',
            'vehicle_make' => 'required|string|max:255',
            'vehicle_model' => 'required|string|max:255',
            'vehicle_color' => 'required|string|max:255',
            'vehicle_year' => 'required|string|max:255',
        ]);

        // Check if vehicle type exist
        $vehicleType = VehicleType::where('id', $request->vehicle_type_id)->first();
        if (!$vehicleType) {
            return redirect()->back()->with('error', 'Vehicle type not found');
        }

        // Check if user exist
        $helper = Helper::where('user_id', Auth::user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Check if Helper Vehicle already exist
        $helperVehicle = HelperVehicle::where('helper_id', $helper->id)->first();

        if (!$helperVehicle) {
            $helperVehicle = new HelperVehicle();
            $helperVehicle->user_id = Auth::user()->id;
            $helperVehicle->helper_id = $helper->id;
        }

        // Set default profile image to null
        $vehicle_image = $helperVehicle->vehicle_image ?? null;
        $thumbnail = $helperVehicle->thumbnail ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('vehicle_image')) {
            $image = Image::read($request->file('vehicle_image'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('vehicle_image')->getClientOriginalExtension();
            $destinationPath = public_path('images/helper_vehicles/');
            $image->save($destinationPath . $imageName);

            // Generate Thumbnail Image Upload on Folder Code
            $destinationPathThumbnail = public_path('images/helper_vehicles/thumbnail/');
            $image->resize(100, 100);
            $image->save($destinationPathThumbnail . $imageName);

            $vehicle_image = $imageName;
            $thumbnail = $imageName;
        }

        $helperVehicle->vehicle_type_id = $request->vehicle_type_id;
        $helperVehicle->vehicle_number = $request->vehicle_number;
        $helperVehicle->vehicle_make = $request->vehicle_make;
        $helperVehicle->vehicle_model = $request->vehicle_model;
        $helperVehicle->vehicle_color = $request->vehicle_color;
        $helperVehicle->vehicle_year = $request->vehicle_year;
        $helperVehicle->vehicle_image = $vehicle_image;
        $helperVehicle->thumbnail = $thumbnail;
        $helperVehicle->save();


        return redirect()->back()->with('success', 'Vehicle info updated successfully!');
    }

    // Route to update address data
    public function socialInfo(Request $request)
    {

        // Check if user exist
        $helper = Helper::where('user_id', Auth::user()->id)->first();

        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        foreach ($request->all() as $key => $link) {
            $socialLink = SocialLink::where('key', $key)
                ->where('user_id', Auth::user()->id)
                ->where('user_type', 'helper')
                ->first();
            if ($socialLink) {
                $socialLink->link = $link;
                $socialLink->save();
            } else {
                $socialLink = new SocialLink();
                $socialLink->user_id = Auth::user()->id;
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
        $helperCompany = HelperCompany::where('user_id', Auth::user()->id)->first();

        if (!$helperCompany) {
            return redirect()->back()->with('error', 'Helper Company not found');
        }

        // Set default profile image to null
        $company_logo = $helperCompany->company_logo ?? null;
        $thumbnail = $helperCompany->thumbnail ?? null;

        // Upload the profile image if provided
        if ($request->hasFile('company_logo')) {
            $image = Image::read($request->file('company_logo'));

            // Main Image Upload on Folder Code
            $imageName = time() . rand(0, 999) . '.' . $request->file('company_logo')->getClientOriginalExtension();
            $destinationPath = public_path('images/company/');
            $image->save($destinationPath . $imageName);

            // Generate Thumbnail Image Upload on Folder Code
            $destinationPathThumbnail = public_path('images/company/thumbnail/');
            $image->resize(100, 100);
            $image->save($destinationPathThumbnail . $imageName);

            $company_logo = $imageName;
            $thumbnail = $imageName;
        }

        // Remove company_logo as file from request
        $request->request->remove('company_logo');

        // Update the helper data with company_logo included
        $helperCompany->update(array_merge($request->all(), ['company_logo' => $company_logo, 'thumbnail' => $thumbnail]));

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

        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return redirect()->back()->with('error', 'Current password does not match');
        }

        if ($request->new_password != $request->confirm_password) {
            return redirect()->back()->with('error', 'Password does not match');
        }

        // Check if user exist
        $user = User::where('id', Auth::user()->id)->first();

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

    public function track_order(Request $request)
    {
        // dd($request->id);
        if (isset($request->id)) {
            $booking = Booking::where('uuid', $request->id)->first();

            if (!$booking) {
                redirect()->back()->with('error', 'Booking not found');
            }

            // dd($booking);
            return view('helper.track_order', compact('booking'));
        }
        return view('helper.track_order');
    }


    public function searchUsers(Request $request)
    {
        $search = $request->input('search');
        $currentUserId = Auth::user()->id; // Get the ID of the current authenticated user

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

    // wallet

    public function wallet()
    {

        // Total Earning
        $statistic['total'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'earned')->where('status', 'success')->sum('amount');

        // WithdrawnAmount
        $statistic['withdrawn'] = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'withdraw')->where('status', 'success')->sum('amount');
        // Get available balance
        $statistic['available'] = $statistic['total'] - $statistic['withdrawn'];

        // Get helper bank accounts
        $helperBankAccounts = HelperBankAccount::where('user_id', Auth::user()->id)->get();

        $wallets = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->get();

        // dd(Auth::user()->id);
        return view('helper.wallet.index', compact('statistic', 'helperBankAccounts', 'wallets'));
    }

    // addBankAccount

    public function addBankAccount(Request $request)
    {
        // Validate request
        $request->validate([
            'account_name' => 'required|string|max:255',
            'account_number' => 'required|string|max:255',
            'payment_method' => 'required|in:paypal,stripe,interac',
        ]);


        // Check if user exist
        $helper = Helper::where('user_id', Auth::user()->id)->first();
        if (!$helper) {
            return redirect()->back()->with('error', 'Helper not found');
        }


        // Check if bank account already exist
        $bankAccount = HelperBankAccount::where('user_id', Auth::user()->id)->where('payment_method', $request->payment_method)->first();
        if ($bankAccount) {
            return redirect()->back()->with('error', 'Bank account already exist');
        }


        // Save bank account
        $bankAccount = new HelperBankAccount();
        $bankAccount->user_id = Auth::user()->id;
        $bankAccount->helper_id = $helper->id;
        $bankAccount->account_name = $request->account_name;
        $bankAccount->account_number = $request->account_number;
        $bankAccount->payment_method = $request->payment_method;
        $bankAccount->is_approved = 0;
        $bankAccount->save();


        return redirect()->back()->with('success', 'Bank account added successfully');
    }

    // withdrawRequest

    public function withdrawRequest(Request $request)
    {

        // Validate request
        $request->validate([]);
        $validator = Validator::make($request->all(), [
            'withdraw_amount' => 'required|numeric|min:0',
            'bank_account_id' => 'required|exists:helper_bank_accounts,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()->with('error', $validator->errors()->first());
        }

        // Check if helper added the accounts and approved
        $helperBankAccounts = HelperBankAccount::where('user_id', Auth::user()->id)->where('is_approved', 1)->get();
        if (!$helperBankAccounts) {
            return redirect()->back()->with('error', 'Please add bank accounts first');
        }

        // Get bank account
        $bankAccount = HelperBankAccount::where('id', $request->bank_account_id)->where('user_id', Auth::user()->id)->first();
        if (!$bankAccount) {
            return redirect()->back()->with('error', 'Bank account not found');
        }

        if ($bankAccount->is_approved == 0) {
            return redirect()->back()->with('error', 'Bank account not approved');
        }

        // Check if withdraw request already exist
        $withdrawRequest = UserWallet::where('user_id', Auth::user()->id)->where('user_type', 'helper')->where('type', 'withdraw')->where('status', 'pending')->first();
        if ($withdrawRequest) {
            return redirect()->back()->with('error', 'Withdraw request already exist');
        }


        // Save withdraw request
        $withdrawRequest = new UserWallet();
        $withdrawRequest->user_id = Auth::user()->id;
        $withdrawRequest->user_type = 'helper';
        $withdrawRequest->type = 'withdraw';
        $withdrawRequest->amount = $request->withdraw_amount;
        $withdrawRequest->status = 'pending';
        $withdrawRequest->payment_method = $bankAccount->payment_method;
        $withdrawRequest->transaction_id = $bankAccount->id;
        $withdrawRequest->save();


        return redirect()->back()->with('success', 'Withdraw request added successfully');
    }
}
