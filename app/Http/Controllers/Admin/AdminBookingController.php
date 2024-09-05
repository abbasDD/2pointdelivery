<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BookingController;
use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\ServiceCategory;
use App\Models\VehicleType;
use Illuminate\Http\Request;

class AdminBookingController extends Controller
{

    protected $getBookingController;

    public function __construct(BookingController $getBookingController)
    {
        $this->getBookingController = $getBookingController;
    }


    public function index(Request $request)
    {
        $bookings = Booking::with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            // ->where('status', '!=', 'draft') //Where booking status is not draft
            ->orderBy('updated_at', 'desc')
            ->paginate(10);

        foreach ($bookings as $booking) {
            if ($booking->helper_user_id != NULL) {
                $booking->helper = Helper::where('user_id', $booking->helper_user_id)->first();
            }

            $booking->client = Client::where('user_id', $booking->client_user_id)->first();

            $booking->moving = null;

            if ($booking->booking_type == 'delivery') {
                $booking->delivery = BookingDelivery::where('booking_id', $booking->id)->first();
            }

            if ($booking->booking_type == 'moving') {
                $booking->moving = BookingMoving::where('booking_id', $booking->id)->first();
            }
        }

        // dd($bookings);


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

        // Get booking detail as per booking type
        $bookingData = $this->getBookingController->getBookingTypeData($booking->booking_type, $booking->id);

        // Convert booking status to current
        $booking->currentStatus = $this->getBookingController->getBookingCurrentStatus($booking->status);

        $booking->moverCount = 0;

        if ($booking->helper_user_id !== null) {
            $booking->moverCount++;
        }

        if ($booking->helper_user_id2 !== null) {
            $booking->moverCount++;
        }


        // Get helper Data
        $helperData = null;
        if ($booking->helper_user_id) {
            $helperData = Helper::where('user_id', $booking->helper_user_id)->first();
        }


        // Get helper2 Data
        $helperData2 = null;
        if ($booking->helper_user_id2) {
            $helperData2 = Helper::where('user_id', $booking->helper_user_id2)->first();
        }

        // dd($helperData2);

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


        // Get helper2 vehicle data
        $helper2VehicleData = null;
        if ($booking->helper_user_id2) {
            $helper2VehicleData = HelperVehicle::where('user_id', $booking->helper_user_id2)->first();
        }


        // dd($bookingData);

        return view('admin.bookings.show', compact('booking', 'bookingData', 'helperData', 'helperData2', 'clientData', 'vehicleTypeData', 'helperVehicleData', 'helper2VehicleData'));
    }

    public function cancel(Request $request)
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

        if ($booking->status == 'cancelled') {
            return redirect()->back()->with('error', 'Booking already cancelled');
        }

        if ($booking->status != 'pending') {
            return redirect()->back()->with('error', 'Booking already in progress');
        }

        $booking->status = 'cancelled';
        $booking->save();

        return redirect()->back()->with('success', 'Booking cancelled successfully');
    }
}
