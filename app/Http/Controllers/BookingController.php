<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\BookingDelivery;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\PaymentSetting;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
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
        $bookings = Booking::where('client_user_id', auth()->user()->id)
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
            'base_price' => 'nullable|string|max:255',
            'distance' => 'nullable|string|max:255',
            'base_distance' => 'nullable|string|max:255',
            'extra_distance_price' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'base_weight' => 'nullable|string|max:255',
            'extra_weight_price' => 'nullable|string|max:255',
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

        // String uuid
        $request->request->add([
            'uuid' => Str::random(32),
        ]);

        // Add client_user_id
        $client = Client::where('user_id', auth()->user()->id)->first();
        // if not found then create
        if (!$client) {
            $newClient = Client::create([
                'user_id' => auth()->user()->id,
            ]);
            $client = Client::where('user_id', auth()->user()->id)->first();
        }

        $client_user_id = $client->id;

        $request->request->add(['client_user_id' => auth()->user()->id]);

        // Add booking_at to current datetime
        $request->request->add(['booking_at' => now()]);

        // Create new booking
        $booking = Booking::create($request->all());

        // Calculate prices

        // Get Distance Price
        $distance_price  = 0;
        if ($request->distance) {
            if ($request->distance > $request->base_distance) {
                $distance_price =  ($request->distance - $request->base_distance) * $request->extra_distance_price;
            } else {
                $distance_price = 0;
            }
            // $distance_price = $request->distance * $request->extra_distance_price;
        }

        // Get weight Price
        $weight_price = 0;
        if ($request->weight > $request->base_weight) {
            $weight_price = ($request->weight - $request->base_weight) * $request->extra_weight_price;
        }
        // if ($request->weight) {
        //     $package_volume = $request->package_length * $request->package_width * $request->package_height / 5000;
        //     // Check which one is greater
        //     if ($request->weight > $package_volume) {
        //         $package_weight = $request->weight;
        //     } else {
        //         $package_weight = $package_volume;
        //     }

        //     if ($request->weight > $request->base_weight) {
        //         $weight_price = $package_weight * $request->extra_weight_price;
        //     }
        // }

        // Get priority_price
        $priority_price  = 0;
        // Check if priority setting exist
        if (isset($request->priority_setting_id)) {
            $priority_setting = PrioritySetting::find($request->priority_setting_id);
            if ($priority_setting) {
                $priority_price = $priority_setting->price;
            }
        }

        // get service_price
        $service_price = $request->base_price;

        // Get vehicle_price
        $vehicle_price = 100;

        if (isset($request->vehicle_price_value)) {
            $vehicle_price = $request->vehicle_price_value;
        }

        // Get tax_price
        $tax_price = 0;

        // helper_fee
        $helper_fee = $service_category->helper_fee;

        // Create Booking Payment
        $paymentBooking = BookingDelivery::create([
            'booking_id' => $booking->id,
            'distance_price' => $distance_price,
            'weight_price' => $weight_price,
            'priority_price' => $priority_price,
            'service_price' => $service_price,
            'vehicle_price' => $vehicle_price,
            'tax_price' => $tax_price,
            'helper_fee' => $helper_fee,
            'total_price' => $request->total_price,
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ]);

        // if unable to create paymentBooking then rollback booking
        if (!$paymentBooking) {
            $booking->delete();
            return response()->json(['success' => false, 'data' => 'Unable to create booking']);
        }

        // Response json with success
        return response()->json(['success' => true, 'data' => $booking, 'message' => 'Booking created successfully'], 201);
    }

    public function payment(Request $request)
    {

        // Check if client completed its profile
        $client = Client::where('user_id', auth()->user()->id)->first();

        if (!$client) {
            return redirect()->route('client.profile')->with('error', 'In order to complete booking please complete your profile');
        }

        // Check if personal detail completed
        if ($client->first_name == null || $client->last_name == null) {
            return redirect()->route('client.profile')->with('error', 'In order to complete booking please complete your profile');
        }

        // Check if address detail completed
        if ($client->city == null || $client->state == null || $client->country == null) {
            return redirect()->route('client.profile')->with('error', 'In order to complete booking please complete your profile');
        }

        // Check if profile is company profile
        if ($client->company_enabled == 1) {
            // Check if company detail completed
            $companyData = ClientCompany::where('user_id', auth()->user()->id)->first();

            if (!$companyData) {
                return redirect()->route('client.profile')->with('error', 'In order to complete booking please complete your profile');
            }

            // Check if company detail completed

            if ($companyData->company_alias == null || $companyData->city == null) {
                return redirect()->route('client.profile')->with('error', 'In order to complete booking please complete your profile');
            }
        }

        // dd($request->id);

        $booking = Booking::where('id', $request->id)
            ->where('client_user_id', auth()->user()->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Get payment settings
        $paymentSetting = PaymentSetting::all();

        $paypalEnabled = false;
        $stripeEnabled = false;

        if (isset($paymentSetting->paypal_client_id) && isset($paymentSetting->paypal_secret_id)) {
            $paypalEnabled = true;
        }

        // for stripe
        if (isset($paymentSetting->stripe_publishable_key) && isset($paymentSetting->stripe_secret_key)) {
            $stripeEnabled = true;
        }

        $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();

        // dd($bookingDelivery);

        return view('frontend.payment_booking', compact('booking', 'bookingDelivery', 'paypalEnabled', 'stripeEnabled'));
    }

    // Make Online Payment using Paypal

    public function createPaypalPayment(Request $request)
    {

        // Retrieve booking ID from the request
        $bookingId = $request->input('booking_id');
        if (!$bookingId) {
            return redirect()->back()->with('error', 'Booking ID not found');
        }
        // Get uuid of booking from id
        $booking = Booking::where('id', $bookingId)->where('client_user_id', auth()->user()->id)->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }
        $booking_uuid = $booking->uuid;
        // dd($booking_uuid);

        // Get Paypal Client ID from payment settings
        $paypal_client_id = PaymentSetting::where('key', 'paypal_client_id')->first();
        if (!$paypal_client_id) {
            return redirect()->back()->with('error', 'Paypal client id not found');
        }
        // Get paypal_secret_id from payment settings
        $paypal_secret_id = PaymentSetting::where('key', 'paypal_secret_id')->first();
        if (!$paypal_secret_id) {
            return redirect()->back()->with('error', 'Paypal secret id not found');
        }
        // Set up PayPal API credentials
        $clientId = $paypal_client_id->value;
        $secret = $paypal_secret_id->value;
        $mode = 'sandbox';

        // Set up PayPal API endpoint
        $apiEndpoint = $mode === 'sandbox' ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';

        // Set up HTTP client
        $httpClient = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth($clientId, $secret);

        // Create PayPal payment
        $response = $httpClient->post("$apiEndpoint/v1/payments/payment", [
            'intent' => 'sale',
            'payer' => [
                'payment_method' => 'paypal',
            ],
            'transactions' => [
                [
                    'amount' => [
                        'total' => $request->total_price, // Set the amount to charge
                        'currency' => 'USD',
                    ],
                    'custom' => $booking_uuid,
                ],
            ],
            'redirect_urls' => [
                'return_url' => route('client.booking.payment.paypal.execute'),
                'cancel_url' => route('client.booking.payment.paypal.cancel'),
            ],
        ]);


        $payment = $response->json();

        // dd($payment);

        if (isset($payment['name']) && $payment['name'] == 'VALIDATION_ERROR') {
            return  redirect()->back()->with('error', 'Invalid request - something went wrong');
        }

        // Redirect to PayPal for approval
        return redirect($payment['links'][1]['href']);
    }

    public function executePaypalPayment(Request $request)
    {
        // Retrieve paymentId and PayerID from the request
        $paymentId = $request->input('paymentId');
        $payerId = $request->input('PayerID');

        // Get Paypal Client ID from payment settings
        $paypal_client_id = PaymentSetting::where('key', 'paypal_client_id')->first();
        if (!$paypal_client_id) {
            return redirect()->back()->with('error', 'Paypal client id not found');
        }
        // Get paypal_secret_id from payment settings
        $paypal_secret_id = PaymentSetting::where('key', 'paypal_secret_id')->first();
        if (!$paypal_secret_id) {
            return redirect()->back()->with('error', 'Paypal secret id not found');
        }
        // Set up PayPal API credentials
        $clientId = $paypal_client_id->value;
        $secret = $paypal_secret_id->value;
        $mode = 'sandbox';

        // Set up PayPal API endpoint
        $apiEndpoint = $mode === 'sandbox' ? 'https://api.sandbox.paypal.com' : 'https://api.paypal.com';

        // Set up HTTP client
        $httpClient = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->withBasicAuth($clientId, $secret);

        // Execute PayPal payment
        $response = $httpClient->post("$apiEndpoint/v1/payments/payment/$paymentId/execute", [
            'payer_id' => $payerId,
        ]);

        // Check if payment was successful
        if ($response->successful()) {
            // Payment successful
            $paymentDetails = $response->json();
            // Get booking_uuid as custom
            $booking_uuid = $paymentDetails['transactions'][0]['custom'];
            // dd($paymentDetails['transactions'][0]['custom']);

            // dd($paymentDetails);

            // Check if booking exist on uuid
            $booking = Booking::where('uuid', $booking_uuid)->first();
            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found');
            }

            // Update booking payment status
            $booking->update(['status' => 'pending', 'payment_status' => 'paid', 'payment_method' => 'paypal']);

            // Update booking payment details
            BookingDelivery::where('booking_id', $booking->id)->update(['transaction_id' =>  $paymentDetails['id'], 'payment_status' => 'paid', 'payment_method' => 'paypal', 'payment_at' => Carbon::now()]);

            // Redirect to booking detail page
            // return redirect()->route('client.booking.show', $booking->id);
            return redirect()->route('client.bookings');

            // Process the payment details and update your database accordingly
            // For example, you might update the booking status to "paid"
            // return redirect()->route('payment.success');
        } else {
            // Payment failed
            $error = $response->json();
            // Handle the payment failure accordingly
            // For example, you might redirect the user to a payment failure page
            // return redirect()->route('payment.failure');

            dd($error);
        }
    }

    public function cancelPaypalPayment()
    {
        // Retrieve booking ID or UUID from the request
        // $bookingId = $request->input('booking_id');

        dd('Payment cancelled');

        // Handle payment cancellation
        return redirect()->back()->with('error', 'Payment cancelled');
    }

    // Make COD Payment
    public function codPayment(Request $request)
    {

        // Check if booking exist on id
        $booking = Booking::find($request->id);

        if (!$booking) {
            return response()->json(['success' => false, 'data' => 'Unable to find booking']);
        }

        $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();

        if ($bookingDelivery->payment_status == 'paid') {
            return response()->json(['success' => false, 'data' => 'Booking already paid']);
        }

        // dd($booking);

        // Update booking to paid status

        $booking->update([
            'status' => 'pending',
        ]);

        $bookingDelivery->update([
            'payment_method' => 'cod',
            'payment_status' => 'paid',
            'payment_at' => Carbon::now(),
        ]);

        return response()->json(['success' => true, 'data' => 'Booking paid successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('client_user_id', auth()->user()->id)
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
            $helperVehicleData = HelperVehicle::where('helper_id', $booking->helper_user_id)->first();
        }



        // dd($vehicleTypeData);

        return view('frontend.bookings.show', compact('booking', 'bookingDelivery', 'helperData', 'clientData', 'vehicleTypeData', 'helperVehicleData'));
    }
}
