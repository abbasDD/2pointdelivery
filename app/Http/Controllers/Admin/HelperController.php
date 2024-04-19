<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;

class HelperController extends Controller
{
    public function index(Request $request)
    {
        $helpers = Helper::paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('helpers.partials.list', compact('helpers'))->render());
        }
        return view('admin.helpers.index', compact('helpers'));
    }

    public function requestedHelpers(Request $request)
    {
        $helpers = Helper::paginate(10); // 10 items per page
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
            'account_type' => 'helper',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        // Create the admin
        $helper = new Helper([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'admin_type' => $request->admin_type,
        ]);

        $helper->save();

        // Redirect with a success message
        return redirect()->route('admin.helpers')->with('success', 'Sub-admin created successfully!');
    }

    public function edit(Request $request)
    {
        $helper = Helper::select('helpers.*', 'users.email', 'users.is_active')
            ->join('users', 'helpers.user_id', '=', 'users.id')
            ->where('helpers.id', $request->id)
            ->first();
        return view('admin.helpers.edit', compact('helper'));
    }
}
