<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\City;
use App\Models\ClientCompany;
use App\Models\Country;
use App\Models\Helper;
use App\Models\Industry;
use App\Models\Referral;
use App\Models\SocialLink;
use App\Models\State;
use App\Models\User;
use App\Models\UserNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image;
use Lab404\Impersonate\Impersonate;

class ClientController extends Controller
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
        // dd(Auth::user()->account_type);
        if (Auth::user()->account_type == 'company') {
            return view('client.auth.complete_company_profile');
        }

        return view('client.auth.complete_profile');
    }

    // Switch to Helper
    public function switchToHelper()
    {

        // Get user data
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user) {
            return redirect()->route('index')->with('error', 'User not found');
        }


        // Get Helper data from DB
        $helper = Helper::where('user_id', Auth::user()->id)->first();

        // If helper not found
        if (!$helper) {
            // Check if Client is created with same id
            $client = Client::where('user_id', Auth::user()->id)->first();

            // If client is found then duplicate data to helper
            if ($client) {

                // Check if client first name and last name is not null
                if ($client->first_name == null || $client->last_name == null || $client->city == null) {
                    return redirect()->route('client.profile')->with('error', 'Please fill your client detail first');
                }

                $helper = Helper::create([
                    'user_id' => Auth::user()->id,
                    'company_enabled' => 0,
                    'first_name' => $client->first_name ?? '',
                    'middle_name' => $client->middle_name ?? '',
                    'last_name' => $client->last_name ?? '',
                    'gender' => $client->gender ?? '',
                    'date_of_birth' => $client->date_of_birth ?? '',
                    'profile_image' => $client->profile_image ?? '',
                    'thumbnail' => $client->thumbnail ?? '',
                    'phone_no' => $client->phone_no ?? '',
                    'suite' => $client->suite ?? '',
                    'street' => $client->street ?? '',
                    'city' => $client->city ?? '',
                    'state' => $client->state ?? '',
                    'country' => $client->country ?? '',
                    'zip_code' => $client->zip_code ?? '',
                ]);
            }
            // If not then create a simple helper
            else {
                $helper = Helper::create([
                    'user_id' => Auth::user()->id,
                ]);
            }

            // Send notification to admin
            UserNotification::create([
                'sender_user_id' => Auth::user()->id,
                'receiver_user_id' => 1,
                'receiver_user_type' => 'admin',
                'type' => 'user_registered',
                'reference_id' => Auth::user()->id,
                'title' => 'New Helper Created',
                'content' => 'New helper has been created.',
                'read' => 0
            ]);
        }

        // @impersonating($guard = null)
        if (!Impersonate::isImpersonating()) {
            // Store login_type to Session
            session(['login_type' => 'helper']);

            // Get helper first name and last name
            $helperInfo = Helper::where('user_id', Auth::user()->id)->first();
            if ($helperInfo) {
                session(['full_name' => $helperInfo->first_name . ' ' . $helperInfo->last_name]);
                // set profile_image
                session(['thumbnail' => asset('images/users/thumbnail/' . $helperInfo->thumbnail)]);
            }
        }


        $user->helper_enabled = 1;
        $user->save();

        // Redirect to dashboard
        return redirect()->route('helper.index')->with('success', 'Switched to Helper Dashboard');
    }

    // Request Copmany Profile
    public function requestCompany(Request $request)
    {
        // Check if user exist
        $client = Client::where('user_id', Auth::user()->id)->first();
        if (!$client) {
            return redirect()->back()->with('error', 'Client not found');
        }

        // Check if client company already exist
        $clientCompany = ClientCompany::where('user_id', Auth::user()->id)->first();
        if ($clientCompany) {
            // update the company_enabled to 1
            $client->company_enabled = 1;
            $client->save();

            return redirect()->back()->with('success', 'Company profile requested successfully');
        }

        // Create a new client company
        $clientCompany = new ClientCompany();
        $clientCompany->user_id = Auth::user()->id;
        $clientCompany->client_id = $client->id;
        $clientCompany->company_alias = $request->company_alias;
        $clientCompany->legal_name = $request->legal_name;
        $clientCompany->save();

        return redirect()->back()->with('success', 'Company profile requested successfully');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        // @impersonating($guard = null)
        if (!Impersonate::isImpersonating()) {
            // Change session variable login_type to client
            session(['login_type' => 'client']);
        }

        // dd(session('login_type'));

        // Statistics
        $satistics = [
            'total_bookings' => Booking::where('client_user_id', Auth::user()->id)->count(),
            'pending_bookings' => Booking::where('client_user_id', Auth::user()->id)->where('status', 'pending')->count(),
            'cancelled_bookings' => Booking::where('client_user_id', Auth::user()->id)->where('status', 'cancelled')->count(),
            'unpaid_bookings' => Booking::where('client_user_id', Auth::user()->id)->where('status', 'draft')->count(),
        ];

        $bookings = Booking::where('client_user_id', Auth::user()->id)
            ->with('client')
            ->with('serviceType')
            ->with('serviceCategory')
            ->whereIn('status', ['draft', 'pending', 'accepted', 'started', 'in_transit'])
            ->orderBy('bookings.updated_at', 'desc')
            ->take(10)->get();

        // Get booking client details

        // Check if user completed profile
        $client_updated = false;  //Set default value
        $client = Client::where('user_id', Auth::user()->id)->first();

        if (isset($client) && $client->first_name != null && $client->zip_code != null) {
            $client_updated = true;
        }

        return view('client.index', compact('bookings', 'satistics', 'client_updated'));
    }

    // Route to load profile to edit
    public function edit_profile()
    {
        // Get client data
        $clientData = Client::where('user_id', Auth::user()->id)->first();

        // Create a new client if not found
        if (!$clientData) {
            $clientData = new Client();
            $clientData->user_id = Auth::user()->id;
            $clientData->save();

            $clientData = Client::where('user_id', Auth::user()->id)->first();
        }

        // Get all social links
        $socialLinks = SocialLink::where('user_id', Auth::user()->id)->where('user_type', 'client')->get();

        $social_links = [];

        // Loop through social links
        foreach ($socialLinks as $socialLink) {
            $social_links[$socialLink->key] = $socialLink->link;
        }

        // dd($social_links);

        // Get client company data
        $clientCompanyData = ClientCompany::where('user_id', Auth::user()->id)->first();
        if (!$clientCompanyData) {
            $clientCompanyData = new ClientCompany();
            $clientCompanyData->user_id = Auth::user()->id;
            $clientCompanyData->client_id = $clientData->id;
            $clientCompanyData->save();
        }

        // dd($clientCompanyData);

        // Get list of all countries
        $countries = Country::all();

        $companyStates = State::where('country_id', $clientCompanyData->country)->get();

        $companyCities = City::where('state_id', $clientCompanyData->state)->get();

        // Client Address Detail

        $clientStates = State::where('country_id', $clientData->country)->get();
        $clientCities = City::where('state_id', $clientData->state)->get();

        // Address Data
        $addressData = [
            'countries' => $countries,
            'clientStates' => $clientStates,
            'clientCities' => $clientCities,
            'companyStates' => $companyStates,
            'companyCities' => $companyCities
        ];

        // dd($clientCompanyData);

        // Get list of industries
        $industries = Industry::all();

        return view('client.profile.edit', compact('clientData', 'social_links', 'clientCompanyData', 'addressData', 'industries'));
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
        ]);

        // Check if user exist
        $client = Client::where('user_id', Auth::user()->id)->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found');
        }

        // Set default profile image to null
        $profile_image = $client->profile_image ?? null;
        $thumbnail = $client->thumbnail ?? null;

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

        // Update the client data
        $client->update([
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'phone_no' => $request->phone_no,
            'date_of_birth' => date('Y-m-d', strtotime($request->date_of_birth)),
            'tax_id' => $request->tax_id,
            'profile_image' => $profile_image,
            'thumbnail' => $thumbnail,
            'company_enabled' => $request->company_enabled,
        ]);
        // dd($request->all());


        // @impersonating($guard = null)
        if (!Impersonate::isImpersonating()) {
            // Update session data as well for name

            session(['full_name' => $client->first_name . ' ' . $client->last_name]);
            // set thumbnail
            if ($client->thumbnail) {
                session(['thumbnail' => asset('images/users/thumbnail/' . $client->thumbnail)]);
            }
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
        $client = Client::where('user_id', Auth::user()->id)->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found');
        }

        // Update the client data
        $client->update($request->all());
        // dd($request->all());

        return redirect()->back()->with('success', 'Profile Address updated successfully!');
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
        $clientCompany = ClientCompany::where('user_id', Auth::user()->id)->first();

        if (!$clientCompany) {
            return redirect()->back()->with('error', 'Client Company not found');
        } // Set default profile image to null
        $company_logo = $clientCompany->company_logo ?? null;
        $thumbnail = $clientCompany->thumbnail ?? null;

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

        // Update the client data with company_logo included
        $clientCompany->update(array_merge($request->all(), ['company_logo' => $company_logo, 'thumbnail' => $thumbnail]));

        // dd($request->all());

        return redirect()->back()->with('success', 'Company Profile info updated successfully!');
    }

    // Route to update address data
    public function socialInfo(Request $request)
    {

        // Check if user exist
        $client = Client::where('user_id', Auth::user()->id)->first();

        if (!$client) {
            return redirect()->back()->with('error', 'Client not found');
        }

        foreach ($request->all() as $key => $link) {
            $socialLink = SocialLink::where('key', $key)
                ->where('user_id', Auth::user()->id)
                ->where('user_type', 'client')
                ->first();
            if ($socialLink) {
                $socialLink->link = $link;
                $socialLink->save();
            } else {
                $socialLink = new SocialLink();
                $socialLink->user_id = Auth::user()->id;
                $socialLink->user_type = 'client';
                $socialLink->key = $key;
                $socialLink->link = $link;
                $socialLink->save();
            }
        }

        return redirect()->back()->with('success', 'Profile Social updated successfully!');
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
            return redirect()->back()->with('error', 'Client not found');
        }

        // Update passowrd
        $user->update([
            'password' => Hash::make($request->new_password)
        ]);

        return redirect()->back()->with('success', 'Profile Address updated successfully!');
    }

    public function referrals()
    {
        // Get all referred list
        $referrals = Referral::select('referrals.*', 'users.email', 'users.user_type')
            ->join('users', 'users.id', '=', 'referrals.referrer_id')
            ->where('referred_user_id', Auth::user()->id)->get();

        return view('client.referrals', compact('referrals'));
    }


    public function track_order(Request $request)
    {
        // dd($request->id);
        $booking = null;
        $bookingPayment = null;

        if (isset($request->id)) {
            $booking = Booking::where('uuid', $request->id)
                ->where('client_user_id', Auth::user()->id)
                ->with('prioritySetting')
                ->with('serviceType')
                ->with('serviceCategory')
                ->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found');
            }
            if ($booking->booking_type == 'delivery') {
                // Getting booking payment data
                $bookingPayment = BookingDelivery::where('booking_id', $booking->id)->first();
            }

            if ($booking->booking_type == 'moving') {
                $bookingPayment = BookingMoving::where('booking_id', $booking->id)->first();
            }

            $booking->currentStatus = 1;
            // switch to manage booking status
            switch ($booking->status) {
                case 'pending':
                    $booking->currentStatus = 0;
                    break;
                case 'cancelled':
                    $booking->currentStatus = 1;
                    break;
                case 'accepted':
                    $booking->currentStatus = 1;
                    break;
                case 'started':
                    $booking->currentStatus = 2;
                    break;
                case 'in_transit':
                    $booking->currentStatus = 3;
                    break;
                case 'completed':
                    $booking->currentStatus = 4;
                    break;
                case 'incomplete':
                    $booking->currentStatus = 4;
                    break;
                case 'expired':
                    $booking->currentStatus = 1;
                    break;
                default:
                    $booking->currentStatus = 1;
                    break;
            }

            // dd($booking);
            return view('client.track_order', compact('booking', 'bookingPayment'));
        }
        return view('client.track_order', compact('booking', 'bookingPayment'));
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
}
