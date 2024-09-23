<?php

// app/Helpers/DateHelper.php
namespace App\Helpers;

use App\Models\Booking;
use Carbon\Carbon;

class BookingHelper
{

    // Mark Booking as Expired
    public static function checkAndMarkBookingExpired()
    {
        // Get All Bookings
        $bookings = Booking::where('status', 'draft')->get();

        foreach ($bookings as $booking) {

            $bookingTimeLeft = self::calculateBookingTimeDifference($booking);

            // Check if booking time left is greater than 0
            if ($bookingTimeLeft <= 0) {

                $booking->update(['status' => 'expired']);
            }
        }

        return true;
    }

    // Calculate Booking Time Difference
    public static function calculateBookingTimeDifference($booking)
    {
        // Check if booking payment time is exceeded
        $bookingTime = Carbon::parse($booking->booking_at); //Booking Time

        $currentTime = Carbon::now(); //Current Time

        // dd($bookingTime, $currentTime);

        // Difference in Minutes
        // $timeDifferenceInSeconds = $currentTime->diffInMinutes($bookingTime);
        $timeDifferenceInSeconds = $bookingTime->diffInSeconds($currentTime);
        // dd($timeDifferenceInSeconds);
        // if 60 minutes passed then cancel booking
        if ($timeDifferenceInSeconds > 3600) {
            $booking->update(['status' => 'expired']);
            return 0;
        }

        // Time Left
        $bookingTimeLeft = (int)(3600 - $timeDifferenceInSeconds);
        // dd($bookingTimeLeft);
        // Convert to minutes and seconds
        // $bookingTimeLeft = (int)($bookingTimeLeft / 60) . ' minutes ' . ($bookingTimeLeft % 60) . ' seconds';

        return $bookingTimeLeft;
    }
}
