<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\Client;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Intervention\Image\Laravel\Facades\Image;


class AdminController extends Controller
{
    public function index()
    {
        // Get list of all admins
        $admins = Admin::with('user')->get();

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

        // Set default profile image to null
        $profile_image = null;
        $thumbnail = null;

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

        // Create the user
        $user = User::create([
            'account_type' => 'admin',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        // Create the admin
        Admin::create([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'admin_type' => $request->admin_type,
            'profile_image' => $profile_image,
            'thumbnail' => $thumbnail
        ]);

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

        // Find the admin
        $admin = Admin::find($request->id);


        if ($admin) {

            $profile_image = $admin->profile_image;
            $thumbnail = $admin->thumbnail;

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

            // Update the admin's attributes
            $admin->update([
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'admin_type' => $request->admin_type,
                'profile_image' => $profile_image,
                'thumbnail' => $thumbnail
            ]);

            // If admin is the auth user
            if (Auth::user()->id == $admin->user_id) {
                // set thumbnail to session
                session(['thumbnail' => asset('images/users/thumbnail/' . $thumbnail)]);
            }

            // Optionally, return a success response or do other actions
            return redirect()->route('admin.admins')->with('success', 'Admin updated successfully!');
        } else {
            // If the admin is not found, handle the error
            return redirect()->back()->with('error', 'Admin not found or not authorized!');
        }
    }

    public function updateStatus(Request $request)
    {
        $admin = Admin::where('id', $request->id)->first();

        $user = User::where('id', $admin->user_id)->first();
        if ($user) {
            $user->update(['is_active' => !$user->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$user->is_active, 'message' => 'User status updated successfully!']);
        }

        return json_encode(['status' => 'error', 'message' => 'User not found']);
    }


    public function showUser(Request $request)
    {
        $user = User::where('id', $request->id)->first();

        if (!$user) {
            return redirect()->back()->with('error', 'User not found or not authorized!');
        }

        if ($user->client_enabled == 1) {
            $client = Client::where('user_id', $user->id)->first();
            if ($client) {
                return redirect()->route('admin.client.show', ['id' => $client->id]);
            }
        }

        if ($user->helper_enabled == 1) {
            $helper = Helper::where('user_id', $user->id)->first();
            if ($helper) {
                return redirect()->route('admin.helper.show', ['id' => $helper->id]);
            }
        }

        return redirect()->back()->with('error', 'User not found or not authorized!');
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
