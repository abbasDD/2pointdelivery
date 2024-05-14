<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingPayment;
use App\Models\Client;
use App\Models\Helper;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function index()
    {

        $bookings = Booking::select('bookings.*', 'booking_payments.helper_fee')
            ->where('helper_user_id', auth()->user()->id)
            ->with('helper')
            ->join('booking_payments', 'bookings.id', '=', 'booking_payments.booking_id')
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
        $booking->helper_user_id = auth()->user()->id;
        $booking->save();

        return redirect()->back()->with('success', 'Booking accepted successfully!');
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {

        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Getting booking payment data
        $bookingPayment = BookingPayment::where('booking_id', $booking->id)->first();

        // Get helper Data
        $helperData = null;
        if ($booking->helper_user_id) {
            $helperData = Helper::where('user_id', $booking->helper_user_id)->first();
        }

        // Get client data
        $clientData = null;
        if ($booking->client_user_id) {
            $clientData = Client::where('user_id', $booking->client_user_id)->first();
        }

        // dd($booking);

        return view('frontend.bookings.show', compact('booking', 'bookingPayment', 'helperData', 'clientData'));
    }
}
