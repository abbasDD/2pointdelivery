<?php

namespace App\Http\Controllers;

use App\Models\BookingReview;
use App\Http\Requests\StoreBookingReviewRequest;
use App\Http\Requests\UpdateBookingReviewRequest;
use App\Models\Booking;
use App\Models\Helper;
use Illuminate\Http\Request;

class BookingReviewController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function reviewBooking(Request $request)
    {
        // dd($request->all());

        // Validate the request
        $request->validate([
            'id' => 'required|integer|exists:bookings,id',
            'rating' => 'required|integer|between:1,5',
            'review' => 'required|string|max:255',
        ]);

        // Check if booking exist on id
        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Check if rating already exist for booking
        $reviewExist = BookingReview::where('booking_id', $booking->id)->first();
        if ($reviewExist) {
            return redirect()->back()->with('error', 'Booking review already exist');
        }

        // Get helper user
        $helperUser = Helper::find($booking->helper_user_id);

        // Save review
        $review = new BookingReview();
        $review->booking_id = $booking->id;
        $review->helper_user_id = $booking->helper_user_id;
        $review->helper_id = $helperUser->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        // Redirect back with success
        return redirect()->back()->with('success', 'Booking review added successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(BookingReview $bookingReview)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(BookingReview $bookingReview)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateBookingReviewRequest $request, BookingReview $bookingReview)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(BookingReview $bookingReview)
    {
        //
    }
}
