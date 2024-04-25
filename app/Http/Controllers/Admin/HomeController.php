<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
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
}
