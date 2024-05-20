<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\Client;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\ServiceCategory;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class BookingController extends Controller
{
    public function index(Request $request)
    {
        $bookings = Booking::with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->paginate(10);


        if ($request->ajax()) {
            return view('admin.bookings.partials.list', compact('bookings'))->render();
        }

        return view('admin.bookings.index', compact('bookings'));
    }

    public function show(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Getting booking payment data
        $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();

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

        // Get vehicle data
        $vehicleTypeData = null;
        if ($booking->service_category_id) {
            $serviceCategoryData = ServiceCategory::where('id', $booking->service_category_id)->first();
            if ($serviceCategoryData) {
                $vehicleTypeData = VehicleType::where('id', $serviceCategoryData->vehicle_type_id)->first();
            }
        }

        // Get helper vehicle data
        $helperVehicleData = null;
        if ($booking->helper_user_id) {
            $helperVehicleData = HelperVehicle::where('user_id', $booking->helper_user_id)->first();
        }


        // dd($booking->helper_user_id);

        return view('admin.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData', 'vehicleTypeData', 'helperVehicleData'));
    }
}
