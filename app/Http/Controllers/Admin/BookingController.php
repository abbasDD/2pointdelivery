<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = collect([
            (object) [
                'id' => 1,
                'priority' => 'Express',
                'receiver_name' => 'John Doe',
                'status' => 'Pending',

            ],
            (object) [
                'id' => 2,
                'priority' => 'Express',
                'receiver_name' => 'John Doe',
                'status' => 'Pending',
            ]
        ]);

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Request $request)
    {
        return view('admin.bookings.show');
    }
}
