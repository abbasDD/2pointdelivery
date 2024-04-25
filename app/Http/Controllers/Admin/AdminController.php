<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function index()
    {
        // Get list of all admins
        $admins = Admin::select('admins.*', 'users.email', 'users.is_active')
            ->join('users', 'admins.user_id', '=', 'users.id')
            ->get();

        return view('admin.admins.index', compact('admins'));
    }

    public function create()
    {
        return view('admin.admins.create');
    }

    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'admin_type' => 'required|string|in:super,sub',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
        ]);

        // Create the user first
        $user = new User([
            'account_type' => 'admin',
            'email' => $request->email,
            'password' => Hash::make($value = $request->password),
        ]);

        $user->save();

        // Create the admin
        $admin = new Admin([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'admin_type' => $request->admin_type,
        ]);

        $admin->save();

        // Redirect with a success message
        return redirect()->route('admin.admins')->with('success', 'Sub-admin created successfully!');
    }

    public function edit(Request $request)
    {
        $admin = Admin::select('admins.*', 'users.email', 'users.is_active')
            ->join('users', 'admins.user_id', '=', 'users.id')
            ->where('admins.id', $request->id)
            ->first();
        return view('admin.admins.edit', compact('admin'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'admin_type' => 'required|string|in:super,sub',
        ]);
        // dd($request->all());
        $admin = Admin::find($request->id); // Using find() instead of where()->first()

        if ($admin) {
            // If the admin is found, update its attributes
            $admin->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.admins')->with('success', 'Admin updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'Admin not found or not authorized!');
        }
    }
}
