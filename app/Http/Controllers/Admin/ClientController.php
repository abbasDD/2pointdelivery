<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::paginate(10); // 10 items per page
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
            'account_type' => 'client',
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        $user->save();

        // Create the admin
        $client = new Client([
            'user_id' => $user->id,
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'admin_type' => $request->admin_type,
        ]);

        $client->save();

        // Redirect with a success message
        return redirect()->route('admin.clients')->with('success', 'Sub-admin created successfully!');
    }

    public function edit(Request $request)
    {
        $client = Client::select('clients.*', 'users.email', 'users.is_active')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->where('clients.id', $request->id)
            ->first();
        return view('admin.clients.edit', compact('client'));
    }
}
