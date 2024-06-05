<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
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
        // Get delivery earning data
        $total_payments_delivery = BookingDelivery::where('payment_status', 'paid')->sum('helper_fee');
        $total_taxes_delivery = BookingDelivery::where('payment_status', 'paid')->sum('tax_price');
        $total_revenue_delivery = BookingDelivery::where('payment_status', 'paid')->sum('sub_total');
        // $total_earnings_delivery = (BookingDelivery::where('payment_status', 'paid')->sum('sub_total') - BookingDelivery::where('payment_status', 'paid')->sum('helper_fee'));
        $total_earnings_delivery = $total_revenue_delivery - $total_taxes_delivery - $total_payments_delivery;

        // Get moving earning data
        $total_payments_moving = BookingMoving::where('payment_status', 'paid')->sum('helper_fee');
        $total_taxes_moving = BookingMoving::where('payment_status', 'paid')->sum('tax_price');
        $total_revenue_moving = BookingMoving::where('payment_status', 'paid')->sum('sub_total');
        // $total_earnings_moving = (BookingMoving::where('payment_status', 'paid')->sum('sub_total') - BookingMoving::where('payment_status', 'paid')->sum('helper_fee'));
        $total_earnings_moving = $total_revenue_moving - $total_taxes_moving - $total_payments_moving;

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
            'total_earnings' => ($total_earnings_delivery + $total_earnings_moving),
            'total_payments' => ($total_payments_delivery + $total_payments_moving),
            'total_taxes' => ($total_taxes_delivery + $total_taxes_moving),
            'total_revenue' => ($total_revenue_delivery + $total_revenue_moving),
            // Old Data
            'delivery_successful' => Booking::where('status', 'completed')->where('booking_type', 'delivery')->count(),
            'delivery_cancelled' => Booking::where('status', 'cancelled')->where('booking_type', 'delivery')->count(),
            'delivery_pending' => Booking::where('status', 'pending')->where('booking_type', 'delivery')->count(),
            'moving_successful' => Booking::where('status', 'completed')->where('booking_type', 'moving')->count(),
            'moving_cancelled' => Booking::where('status', 'cancelled')->where('booking_type', 'moving')->count(),
            'moving_pending' => Booking::where('status', 'pending')->where('booking_type', 'moving')->count(),
        ];

        // Top Helpers List
        $newHelpers = [
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

        // Helper Requets
        $helperRequests = Helper::select('helpers.*', 'users.email', 'users.is_active')
            ->join('users', 'users.id', '=', 'helpers.user_id')
            ->where('helpers.is_approved', 0)->get();

        // Get last 12 months data of delivery and moving
        $deliveryMovingChartData = [
            'labels' => [],
            'delivery' => [],
            'moving' => [],
        ];
        for ($i = 11; $i >= 0; $i--) {
            $deliveryMovingChartData['labels'][] = date('M', strtotime("-$i month"));
            $deliveryMovingChartData['delivery'][] = Booking::where('booking_type', 'delivery')->whereMonth('created_at', date('m', strtotime("-$i month")))->whereYear('created_at', date('Y', strtotime("-$i month")))->count();
            $deliveryMovingChartData['moving'][] = Booking::where('booking_type', 'moving')->whereMonth('created_at', date('m', strtotime("-$i month")))->whereYear('created_at', date('Y', strtotime("-$i month")))->count();
        }

        // Dummy Data for Chart
        // $chartData = [
        //     'labels' => ['January', 'February', 'March', 'April', 'May'],
        //     'delivery' => [65, 59, 20, 71, 56],
        //     'moving' => [55, 9, 40, 51, 76],
        // ];

        // Latest Bookings
        $latestBookings = Booking::with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            // ->where('status', '!=', 'draft') //Where booking status is not draft
            ->latest()->take(5)->get();
        // dd($latestBookings);

        return view('admin.index', compact('helperRequests', 'deliveryMovingChartData', 'statistics', 'latestBookings'));

        // return view('admin.index', compact('chartData'));
    }
}
