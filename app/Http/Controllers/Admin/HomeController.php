<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Helper;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
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


    // Dashboard
    public function index()
    {
        // Statistics
        $statistics = [
            // Bookings Data
            'total_bookings' => Booking::count(),
            'successful_bookings' => Booking::where('status', 'completed')->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'cancelled_bookings' => Booking::where('status', 'cancelled')->count(),
            // Users Data
            'total_users' => User::where('user_type', 'user')->count(),
            'total_helpers' => User::where('client_enabled', 1)->count(),
            'total_clients' => User::where('helper_enabled', 1)->count(),
            'requested_helpers' => Helper::where('is_approved', 0)->count(),
            // Earning Data
            'total_earnings' => 250,
            'total_commission' => 250,
            'total_taxes' => 250,
            'total_revenue' => 250,
            // Old Data
            'delivery_successful' => Booking::where('status', 'completed')->where('booking_type', 'delivery')->count(),
            'delivery_cancelled' => Booking::where('status', 'cancelled')->where('booking_type', 'delivery')->count(),
            'delivery_pending' => Booking::where('status', 'pending')->where('booking_type', 'delivery')->count(),
            'moving_successful' => Booking::where('status', 'completed')->where('booking_type', 'moving')->count(),
            'moving_cancelled' => Booking::where('status', 'cancelled')->where('booking_type', 'moving')->count(),
            'moving_pending' => Booking::where('status', 'pending')->where('booking_type', 'moving')->count(),
        ];

        // Top Helpers List
        $requestedHelpers = [
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

        // Get last 6 months data of delivery and moving
        $lastSixMonths = [
            'labels' => [],
            'delivery' => [],
            'moving' => [],
        ];
        for ($i = 0; $i < 6; $i++) {
            $lastSixMonths['labels'][] = date('F', strtotime("-$i month"));
            $lastSixMonths['delivery'][] = Booking::where('booking_type', 'delivery')->whereMonth('created_at', date('m', strtotime("-$i month")))->whereYear('created_at', date('Y', strtotime("-$i month")))->count();
            $lastSixMonths['moving'][] = Booking::where('booking_type', 'moving')->whereMonth('created_at', date('m', strtotime("-$i month")))->whereYear('created_at', date('Y', strtotime("-$i month")))->count();
        }

        // Dummy Data for Chart
        // $chartData = [
        //     'labels' => ['January', 'February', 'March', 'April', 'May'],
        //     'delivery' => [65, 59, 20, 71, 56],
        //     'moving' => [55, 9, 40, 51, 76],
        // ];

        // Latest Bookings
        $latestBookings = Booking::latest()->take(5)->get();

        return view('admin.index', compact('requestedHelpers', 'lastSixMonths', 'statistics', 'latestBookings'));

        // return view('admin.index', compact('chartData'));
    }
}
