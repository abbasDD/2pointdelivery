<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BookingReview;
use Illuminate\Http\Request;

class AdminReviewController extends Controller
{
    public function index()
    {
        $reviews = BookingReview::with('booking')->paginate(10); // 10 items per page
        // dd($reviews);
        if (request()->ajax()) {
            return response()->json(view('admin.reviews.partials.list', compact('reviews'))->render());
        }
        return view('admin.reviews.index', compact('reviews'));
    }

    public function updateStatus(Request $request)
    {
        $bookingReview = BookingReview::where('id', $request->id)->first();
        if ($bookingReview) {
            $bookingReview->update(['is_approved' => !$bookingReview->is_approved]);
            return json_encode(['status' => 'success', 'is_approved' => !$bookingReview->is_approved, 'message' => 'Service Category status updated successfully!']);
        }

        return json_encode(['status' => 'error', 'message' => 'Booking not found']);
    }
}
