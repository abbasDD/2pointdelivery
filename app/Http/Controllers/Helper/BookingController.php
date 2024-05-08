<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function index()
    {

        $bookings = Booking::where('helper_id', auth()->user()->id)
            ->with('helper')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')->get();

        return view('helper.bookings.index', compact('bookings'));
    }

    public function acceptBooking(Request $request)
    {
        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'pending') {
            return redirect()->back()->with('error', 'Booking already accepted');
        }

        $booking->status = 'accepted';
        $booking->helper_id = auth()->user()->id;
        $booking->save();

        return redirect()->back()->with('success', 'Booking accepted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('helper_id', auth()->user()->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        return view('frontend.booking_detail', compact('booking'));
    }
}
