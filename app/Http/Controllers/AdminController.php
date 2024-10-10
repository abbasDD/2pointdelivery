<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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




    public function clients(Request $request)
    {
        $clients = Client::paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('clients.partials.client_list', compact('clients'))->render());
        }
        return view('admin.clients.index', compact('clients'));
    }

    public function createClient()
    {
        return view('admin.clients.create');
    }
    public function storeClient(Request $request)
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

    public function editClient(Request $request)
    {
        $client = Client::select('clients.*', 'users.email', 'users.is_active')
            ->join('users', 'clients.user_id', '=', 'users.id')
            ->where('clients.id', $request->id)
            ->first();
        return view('admin.clients.edit', compact('client'));
    }

    public function helpers()
    {
        return view('admin.helpers');
    }

    public function orders()
    {
        return view('admin.orders');
    }

    public function settings()
    {
        return view('admin.settings');
    }

    public function services()
    {
        return view('admin.services');
    }

    public function vehicles()
    {
        return view('admin.vehicles');
    }



    //Code ends here
}
