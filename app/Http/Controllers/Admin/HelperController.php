<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class HelperController extends Controller
{
    public function index(Request $request)
    {
        $helpers = Helper::select('helpers.*', 'users.email', 'users.is_active')
            ->join('users', 'helpers.user_id', '=', 'users.id')
            ->where('helpers.is_approved', 1)
            ->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('helpers.partials.list', compact('helpers'))->render());
        }
        return view('admin.helpers.index', compact('helpers'));
    }

    public function requestedHelpers(Request $request)
    {
        $helpers = Helper::select('helpers.*', 'users.email', 'users.is_active')
            ->join('users', 'helpers.user_id', '=', 'users.id')
            ->where('helpers.is_approved', 0)
            ->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('helpers.partials.list', compact('helpers'))->render());
        }
        return view('admin.helpers.requested', compact('helpers'));
    }

    public function create()
    {
        return view('admin.helpers.create');
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user first
        $user = new User([
            'user_type' => 'user',
            'helper_enabled' => 0,
            'helper_enabled' => 1,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        // Create the admin
        $helper = new Helper([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'company_enabled' => $request->company_enabled,
            'tax_id' => $request->tax_id,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth
        ]);

        $helper->save();

        // Redirect with a success message
        return redirect()->route('admin.helpers')->with('success', 'Helper created successfully!');
    }

    public function edit(Request $request)
    {
        $helper = Helper::select('helpers.*', 'users.email', 'users.is_active')
            ->join('users', 'helpers.user_id', '=', 'users.id')
            ->where('helpers.id', $request->id)
            ->first();
        return view('admin.helpers.edit', compact('helper'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',

        ]);
        // dd($request->all());
        $helper = Helper::find($request->id); // Using find() instead of where()->first()

        if ($helper) {
            // If the admin is found, update its attributes
            $helper->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.helpers')->with('success', 'Helper updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'Helper not found or not authorized!');
        }
    }

    public function show(Request $request)
    {
        $helper = Helper::select('helpers.*', 'users.email', 'users.is_active')
            ->join('users', 'helpers.user_id', '=', 'users.id')
            ->where('helpers.id', $request->id)
            ->first();

        // Redirect to listing page if not found
        if (!$helper) {
            return redirect()->route('admin.helpers')->with('error', 'Helper not found');
        }

        // Get booking of the helper
        $bookings = Booking::where('helper_user_id', $helper->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->paginate(10);

        return view('admin.helpers.show', compact('helper', 'bookings'));
    }


    public function updateStatus(Request $request)
    {
        $helper = Helper::where('id', $request->id)
            ->first();
        $user = User::where('id', $helper->user_id)->first();
        if ($user) {
            $user->update(['is_active' => !$user->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$user->is_active, 'message' => 'User status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    public function resetPassword(Request $request)
    {
        $helper = Helper::where('id', $request->id)
            ->first();
        $user = User::where('id', $helper->user_id)->first();
        if ($user) {
            $user->update(['password' => Hash::make($request->password)]);
            return json_encode(['status' => 'success', 'is_active' => !$user->is_active, 'message' => 'User password updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    public function approve(Request $request)
    {
        $helper = Helper::where('id', $request->id)
            ->first();
        if ($helper) {
            $helper->update(['is_approved' => 1]);
            // Update user status is_active to 1
            $user = User::where('id', $helper->user_id)->first();
            $user->update(['is_active' => 1]);

            // Reuurn json with success
            return json_encode(['status' => 'success', 'message' => 'Helper approved successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Helper not found']);
    }

    public function reject(Request $request)
    {
        $helper = Helper::where('id', $request->id)
            ->first();
        if ($helper) {
            $helper->update(['is_approved' => 2]); // 2 is rejected
            return json_encode(['status' => 'success', 'message' => 'Helper rejected successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Helper not found']);
    }
}
