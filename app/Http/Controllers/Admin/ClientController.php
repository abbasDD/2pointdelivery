<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::select('clients.*', 'users.email', 'users.is_active')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('clients.partials.client_list', compact('clients'))->render());
        }
        return view('admin.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('admin.clients.create');
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
            'client_enabled' => 1,
            'helper_enabled' => 0,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        // Create the client
        $client = new Client([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'middle_name' => $request->middle_name,
            'last_name' => $request->last_name,
            'company_enabled' => $request->company_enabled,
            'tax_id' => $request->tax_id,
            'gender' => $request->gender,
            'date_of_birth' => $request->date_of_birth
        ]);

        $client->save();

        // Redirect with a success message
        return redirect()->route('admin.clients')->with('success', 'Client created successfully!');
    }

    public function edit(Request $request)
    {
        $client = Client::select('clients.*', 'users.email', 'users.is_active')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->where('clients.id', $request->id)
            ->first();

        // Redirect to listing page if not found
        if (!$client) {
            return redirect()->route('admin.clients')->with('error', 'Client not found');
        }
        return view('admin.clients.edit', compact('client'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
        ]);
        // dd($request->all());
        $client = Client::find($request->id); // Using find() instead of where()->first()

        if ($client) {
            // If the admin is found, update its attributes
            $client->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.clients')->with('success', 'Client updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'CLient not found or not authorized!');
        }
    }

    public function updateStatus(Request $request)
    {
        $client = Client::where('id', $request->id)
            ->first();
        $user = User::where('id', $client->user_id)->first();
        if ($user) {
            $user->update(['is_active' => !$user->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$user->is_active, 'message' => 'User status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'User not found']);
    }

    public function show(Request $request)
    {
        $client = Client::select('clients.*', 'users.email', 'users.is_active')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->where('clients.id', $request->id)
            ->first();

        // Redirect to listing page if not found
        if (!$client) {
            return redirect()->route('admin.clients')->with('error', 'Client not found');
        }

        // Get booking of the client
        $bookings = Booking::where('user_id', $client->user_id)
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')
            ->paginate(10);

        return view('admin.clients.show', compact('client', 'bookings'));
    }
}
