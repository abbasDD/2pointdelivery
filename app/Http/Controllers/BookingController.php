<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\Client;
use App\Models\ServiceCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

class BookingController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookings = Booking::where('user_id', auth()->user()->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.created_at', 'desc')->get();
        return view('client.bookings.index', compact('bookings'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validate request
        $validator = Validator::make($request->all(), [
            'service_type_id' => 'required|integer|exists:service_types,id',
            'priority_setting_id' => 'required|integer|exists:priority_settings,id',
            'service_category_id' => 'required|string|exists:service_categories,uuid',
            'pickup_address' => 'required|string|max:255',
            'dropoff_address' => 'required|string|max:255',
            'pickup_latitude' => 'required|string|max:255',
            'pickup_longitude' => 'required|string|max:255',
            'dropoff_latitude' => 'required|string|max:255',
            'dropoff_longitude' => 'required|string|max:255',
            'booking_date' => 'required|string|max:255',
            'booking_time' => 'required|string|max:255',
            'booking_type' => 'required|string|max:255',
            'total_price' => 'required|string|max:255',
        ]);

        // Get service_category_id from uuid
        $service_category = ServiceCategory::where('uuid', $request->service_category_id)->first();
        if ($service_category) {
            $request->request->add(['service_category_id' => $service_category->id]);
        }

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        // Add user_id 
        $request->request->add(['user_id' => auth()->user()->id]);

        // String uuid
        $request->request->add([
            'uuid' => Str::random(32),
        ]);

        // Add client_id
        $client_id = Client::where('user_id', auth()->user()->id)->first()->id;
        $request->request->add(['client_id' => $client_id]);

        // Add booking_at to current datetime
        $request->request->add(['booking_at' => now()]);

        // Create new booking
        $booking = Booking::create($request->all());

        // Response json with success
        return response()->json(['success' => true, 'data' => $booking, 'message' => 'Booking created successfully'], 201);
    }

    public function payment(Request $request)
    {
        // dd($request->id);

        $booking = Booking::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }
        // dd($booking->serviceType->name);

        return view('frontend.payment_booking', compact('booking'));
    }

    // Make COD Payment
    public function codPayment(Request $request)
    {

        // Check if booking exist on id
        $booking = Booking::find($request->id);

        if (!$booking) {
            return response()->json(['success' => false, 'data' => 'Unable to find booking']);
        }

        if ($booking->payment_status == 'paid') {
            return response()->json(['success' => false, 'data' => 'Booking already paid']);
        }

        // dd($booking);

        // Update booking to paid status

        $booking->update([
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'status' => 'pending',
        ]);

        return response()->json(['success' => true, 'data' => 'Booking paid successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('user_id', auth()->user()->id)
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
