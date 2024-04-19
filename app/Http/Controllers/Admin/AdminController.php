<?php

namespace App\Http\Controllers;

use App\Models\Admin;
use App\Http\Requests\StoreAdminRequest;
use App\Http\Requests\UpdateAdminRequest;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class AdminController extends Controller
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

    public function index()
    {
        // Top Helpers List
        $topHelpers = [
            [
                'name' => 'John Doe',
                'image' => 'https://via.placeholder.com/50x50',
                'email' => 'johndoe@gmail.com',
            ],
            [
                'name' => 'Ghulam Abbas',
                'image' => 'https://via.placeholder.com/50x50',
                'email' => 'ghulamabbas@gmailcom',
            ],
            [
                'name' => 'Bob Smith',
                'image' => 'https://via.placeholder.com/50x50',
                'email' => 'bobsmith@gmailcom',
            ],
            [
                'name' => 'Abdul Shakoor',
                'image' => 'https://via.placeholder.com/50x50',
                'email' => 'abdulshakoor@gmailcom',
            ]
        ];

        // Dummy Data for Chart
        $chartData = [
            'labels' => ['January', 'February', 'March', 'April', 'May'],
            'delivery' => [65, 59, 20, 71, 56],
            'moving' => [55, 9, 40, 51, 76],
        ];

        return view('admin.index', compact('topHelpers', 'chartData'));

        // return view('admin.index', compact('chartData'));
    }


    public function subadmins()
    {
        // Get list of all admins
        $subadmins = Admin::select('admins.*', 'users.email', 'users.is_active')
            ->join('users', 'admins.user_id', '=', 'users.id')
            ->get();

        return view('admin.subadmins.index', compact('subadmins'));
    }

    public function createSubadmin()
    {
        return view('admin.subadmins.create');
    }

    public function storeSubadmin(Request $request)
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
            'password' => Hash::make($request->password),
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
        return redirect()->route('admins.index')->with('success', 'Sub-admin created successfully!');
    }

    public function editSubadmin(Request $request)
    {
        $subadmin = Admin::select('admins.*', 'users.email', 'users.is_active')
            ->join('users', 'admins.user_id', '=', 'users.id')
            ->where('admins.id', $request->id)
            ->first();
        return view('admin.subadmins.edit', compact('subadmin'));
    }


    public function updateSubadmin(Request $request)
    {
        return redirect()->route('admin.subadmins')->with('success', 'Client updated successfully!');
    }

    public function updateStatusSubadmin(Request $request)
    {
        $serviceCategory = Admin::where('id', $request->id)
            ->first();
        $serviceCategory->update(['is_active' => !$serviceCategory->is_active]);
        return redirect()->route('admin.serviceCategories')->with('success', 'Service Category Status updated successfully!');
    }

    //Code ends here
}
