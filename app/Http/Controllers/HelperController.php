<?php

namespace App\Http\Controllers;

use App\Models\Helper;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreHelperRequest;
use App\Http\Requests\UpdateHelperRequest;
use App\Models\Booking;
use App\Models\SocialLink;
use Illuminate\Http\Request;

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
        if ($helper) {
            $helper_id = $helper->id;
        }

        // Statistics
        $satistics = [
            'total_bookings' => Booking::where('user_id', $helper_id)->count(),
            'pending_bookings' => Booking::where('user_id', $helper_id)->where('status', 'pending')->count(),
            'cancelled_bookings' => Booking::where('user_id', $helper_id)->where('status', 'cancelled')->count(),
            'unpaid_bookings' => Booking::where('user_id', $helper_id)->where('payment_status', 'unpaid')->count(),
        ];

        $bookings = Booking::where('status', 'pending')
            ->with('helper')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')
            ->take(10)->get();

        return view('helper.index', compact('bookings', 'satistics'));
    }


    public function kyc_details()
    {
        return view('helper.kyc_details');
    }

    public function bookings()
    {

        $bookings = Booking::where('helper_id', auth()->user()->id)
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

    public function edit_profile()
    {
        // Get helper data
        $helperData = Helper::where('user_id', auth()->user()->id)->first();
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

        return view('helper.profile.edit', compact('helperData', 'social_links'));
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
            'tax_id' => 'required|string|max:255',
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
            'last_name' => $request->last_name,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth,
            'tax_id' => $request->tax_id,
            'profile_image' => $profile_image
        ]);
        // dd($request->all());

        return redirect()->back()->with('success', 'Profile info updated successfully!');
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

        return redirect()->back()->with('success', 'Profile Address updated successfully!');
    }


    public function teams()
    {
        return view('helper.teams');
    }

    public function track_order()
    {
        return view('helper.track_order');
    }
}
