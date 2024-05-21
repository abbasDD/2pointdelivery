<?php

namespace App\Http\Controllers\Helper;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\Client;
use App\Models\Helper;
use App\Models\HelperCompany;
use App\Models\HelperVehicle;
use App\Models\ServiceCategory;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BookingController extends Controller
{

    public function index()
    {

        $bookings = Booking::select('bookings.*', 'booking_deliveries.helper_fee')
            ->where('helper_user_id', auth()->user()->id)
            ->with('helper')
            ->join('booking_deliveries', 'bookings.id', '=', 'booking_deliveries.booking_id')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')->get();

        return view('helper.bookings.index', compact('bookings'));
    }

    public function acceptBooking(Request $request)
    {

        // Check if helper completed its profile
        $helper = Helper::where('user_id', auth()->user()->id)->first();

        if (!$helper) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if personal detail completed
        if ($helper->first_name == null || $helper->last_name == null) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if address detail completed
        if ($helper->city == null || $helper->state == null || $helper->country == null) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if vehicle detail completed
        $helperVehicle = HelperVehicle::where('user_id', auth()->user()->id)->first();
        if (!$helperVehicle) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if profile is company profile
        if ($helper->company_enabled == 1) {
            // Check if company detail completed
            $companyData = HelperCompany::where('user_id', auth()->user()->id)->first();

            if (!$companyData) {
                return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
            }

            // Check if company detail completed

            if ($companyData->company_alias == null || $companyData->city == null) {
                return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
            }
        }

        // Check if client approve helper
        if ($helper->is_approved == 0) {
            return redirect()->route('helper.index')->with('error', 'Admin have not accept your request yet');
        }


        $booking = Booking::find($request->id);

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->client_user_id == auth()->user()->id) {
            return redirect()->back()->with('error', 'You can not accept your own booking');
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

        // Client view false
        $clientView = false;

        // Helper view true
        $helperView = true;

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
        // dd($helperVehicleData);

        // dd($booking);

        return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData', 'vehicleTypeData', 'helperVehicleData', 'clientView', 'helperView'));
    }
    // Start Booking
    public function start(Request $request)
    {

        // return redirect()->back()->with('error', 'Booking not found');

        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'accepted') {
            return redirect()->back()->with('error', 'Booking not accepted');
        }

        // Get booking delivery data
        $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
        if (!$bookingDelivery) {
            return redirect()->back()->with('error', 'Booking delivery not found');
        }

        // if start_booking_image is not set then back with error
        if (!$request->hasFile('start_booking_image')) {
            return redirect()->back()->with('error', 'Please select start booking image');
        }

        // if start_booking_image is not set then back with error
        if (!$request->hasFile('signatureStart')) {
            return redirect()->back()->with('error', 'Please select start booking image');
        }

        $start_booking_image = null;

        $signatureStart = null;


        // Upload booking image
        if ($request->hasFile('start_booking_image')) {
            $file = $request->file('start_booking_image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $start_booking_image = $updatedFilename;
        }

        // Upload signature start image
        if ($request->hasFile('signatureStart')) {
            $file = $request->file('signatureStart');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $signatureStart = $updatedFilename;
        }


        $bookingDelivery->signatureStart = $signatureStart;
        $bookingDelivery->start_booking_image = $start_booking_image;
        $bookingDelivery->start_booking_at = Carbon::now();
        $bookingDelivery->save();

        $booking->status = 'started';
        $booking->save();

        // dd($booking);

        return redirect()->back()->with('success', 'Booking started successfully!');

        // return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData'));
    }

    // inTransit Booking
    public function inTransit(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'started') {
            return redirect()->back()->with('error', 'Booking not started');
        }

        $booking->status = 'in_transit';
        $booking->save();

        // Get booking delivery data
        $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
        if (!$bookingDelivery) {
            return redirect()->back()->with('error', 'Booking delivery not found');
        }

        $bookingDelivery->start_intransit_at = Carbon::now();
        $bookingDelivery->save();

        return redirect()->back()->with('success', 'Booking in transit successfully!');
    }

    // Start Booking
    public function complete(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('helper_user_id', auth()->user()->id)
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        if ($booking->status != 'in_transit') {
            return redirect()->back()->with('error', 'Booking is not in transit');
        }

        // Get booking delivery data
        $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
        if (!$bookingDelivery) {
            return redirect()->back()->with('error', 'Booking delivery not found');
        }

        // if complete_booking_image is not set then back with error
        if (!$request->hasFile('complete_booking_image')) {
            return redirect()->back()->with('error', 'Please select complete booking image');
        }

        // if start_booking_image is not set then back with error
        if (!$request->hasFile('signatureCompleted')) {
            return redirect()->back()->with('error', 'Please select start booking image');
        }

        $complete_booking_image = null;

        $signatureCompleted = null;

        // Upload booking image
        if ($request->hasFile('complete_booking_image')) {
            $file = $request->file('complete_booking_image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $complete_booking_image = $updatedFilename;
        }

        // Upload completed signature image
        if ($request->hasFile('signatureCompleted')) {
            $file = $request->file('signatureCompleted');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/bookings/');
            $file->move($destinationPath, $updatedFilename);

            // Set the profile image attribute to the new file name
            $signatureCompleted = $updatedFilename;
        }

        $bookingDelivery->signatureCompleted = $signatureCompleted;
        $bookingDelivery->complete_booking_image = $complete_booking_image;
        $bookingDelivery->complete_booking_at = Carbon::now();
        $bookingDelivery->save();

        $booking->status = 'completed';
        $booking->save();

        // dd($booking);

        return redirect()->back()->with('success', 'Booking completed successfully!');

        // return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData'));
    }
}
