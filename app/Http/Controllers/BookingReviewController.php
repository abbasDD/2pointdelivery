<?php

namespace App\Http\Controllers;

use App\Models\BookingReview;
use App\Http\Requests\StoreBookingReviewRequest;
use App\Http\Requests\UpdateBookingReviewRequest;
use App\Models\Booking;
use App\Models\Helper;
use App\Models\UserNotification;
use Illuminate\Http\Request;

class BookingReviewController extends Controller
{

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
            'review' => 'required',
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
        $helperUser = Helper::where('user_id', $booking->helper_user_id)->first();

        if (!$helperUser) {
            return redirect()->back()->with('error', 'Helper not found');
        }

        // Save review
        $review = new BookingReview();
        $review->booking_id = $booking->id;
        $review->helper_user_id = $booking->helper_user_id;
        $review->helper_id = $helperUser->id;
        $review->rating = $request->rating;
        $review->review = $request->review;
        $review->save();

        // Notification
        // Notification
        UserNotification::create([
            'sender_user_id' => auth()->user()->id,
            'receiver_user_id' => $review->helper_user_id,
            'receiver_user_type' => 'helper',
            'type' => 'booking',
            'reference_id' => $booking->id,
            'title' => 'Client left a review',
            'content' => 'Client left' . $review->rating . ' star review',
            'read' => 0
        ]);

        // Redirect back with success
        return redirect()->back()->with('success', 'Booking review added successfully');
    }
}
