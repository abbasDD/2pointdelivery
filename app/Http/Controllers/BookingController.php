<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Http\Requests\StoreBookingRequest;
use App\Http\Requests\UpdateBookingRequest;
use App\Models\AddressBook;
use App\Models\BookingDelivery;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Helper;
use App\Models\HelperVehicle;
use App\Models\PaymentSetting;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\TaxSetting;
use App\Models\VehicleType;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use App\Http\Controllers\GetEstimateController;
use App\Models\BookingMoving;
use App\Models\BookingReview;
use App\Models\UserNotification;

class BookingController extends Controller
{

    protected $getEstimateController;

    public function __construct(GetEstimateController $getEstimateController)
    {
        $this->middleware('auth');

        $this->getEstimateController = $getEstimateController;
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
            ->orderBy('bookings.updated_at', 'desc')->get();

        foreach ($bookings as $booking) {
            if ($booking->helper_user_id != NULL) {
                $booking->helper = Helper::where('user_id', $booking->helper_user_id)->first();
            }

            $booking->client = Client::where('user_id', $booking->client_user_id)->first();

            $booking->moving = null;

            if ($booking->booking_type == 'delivery') {
                $booking->delivery = BookingDelivery::where('booking_id', $booking->id)->first();
            }
        }

        return view('client.bookings.index', compact('bookings'));
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

        // Check if service type available for booking
        $serviceType = ServiceType::where('id', $request->service_type_id)->where('is_active', 1)->first();
        if ($serviceType) {
            $request->request->add(['service_type_id' => $serviceType->id]);
        }

        // Get service_category_id from uuid
        $serviceCategory = ServiceCategory::where('uuid', $request->service_category_id)->first();
        if ($serviceCategory) {
            $request->request->add(['service_category_id' => $serviceCategory->id]);
        }

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        // Generate uuid
        $uuid = Str::random(8);

        // Generate uuid and ensure it is unique
        do {
            $uuid = Str::random(8);
            $booking = Booking::where('uuid', $uuid)->first();
        } while ($booking);

        // String uuid
        $request->request->add([
            'uuid' => $uuid,
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

        // Get package value and calculate insurance
        $insurance_value = $this->getEstimateController->getInsuranceValue($request->selectedServiceType, $request->package_value);

        // Get Base Price Value
        $service_price = $this->getEstimateController->getBasePrice($serviceType->type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);

        // Distance Price
        $distance_price = $this->getEstimateController->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $request->distance_in_km);

        // Get priority_price
        $priority_price  = 0;
        // Check if priority setting exist
        if (isset($request->priority_setting_id)) {
            $priority_setting = PrioritySetting::find($request->priority_setting_id);
            if ($priority_setting) {
                $priority_price = $priority_setting->price;
            }
        }

        // Vehicle Price
        $vehicle_price = $this->getEstimateController->getVehiclePrice($serviceType->type, $serviceCategory->vehicle_type_id, $request->distance_in_km);

        // Weight Price
        $weight_price = $this->getEstimateController->getWeightPrice($serviceType->type, $serviceCategory, $request->package_weight, $request->package_length, $request->package_width, $request->package_height, $request->selectedMovingDetailsID);


        // If service type is moving
        $no_of_room_price = 0;
        $floor_plan_price = 0;
        $floor_assess_price = 0;
        $job_details_price = 0;

        if ($serviceType->type == 'moving') {
            // Get Room Price
            $no_of_room_price = $this->getEstimateController->getNoOfRoomPrice($request->selectedNoOfRoomID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Plan Price
            $floor_plan_price = $this->getEstimateController->getFloorPlanPrice($request->selectedFloorPlanID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Access Price
            $floor_assess_price = $this->getEstimateController->getFloorAccessPrice($request->selectedFloorAssessID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Job Details Price
            if ($request->selectedJobDetailsID != '') {
                $job_details_price = $this->getEstimateController->getJobDetailsPrice($request->selectedJobDetailsID, $serviceCategory, $request->floor_size, $request->no_of_hours);
            }
        }

        // Sub Total
        $sub_total = $service_price + $distance_price + $priority_price + $vehicle_price + $weight_price + $no_of_room_price + $floor_plan_price + $floor_assess_price + $job_details_price;


        //  Tax Price
        $tax_price = $this->getEstimateController->getTaxPrice($sub_total);


        // Total amountToPay
        $amountToPay = $service_price + $distance_price + $priority_price + $vehicle_price + $weight_price + $no_of_room_price + $floor_plan_price + $floor_assess_price + $job_details_price + $tax_price;

        // helper_fee
        $helper_fee = $serviceCategory->helper_fee;


        if ($serviceType->type == 'delivery') {
            // Create Booking Payment
            $deliveryBooking = BookingDelivery::create([
                'booking_id' => $booking->id,
                'distance_price' => number_format((float)$distance_price, 2, '.', ''),
                'weight_price' => number_format((float)$weight_price, 2, '.', ''),
                'priority_price' => number_format((float)$priority_price, 2, '.', ''),
                'service_price' => number_format((float)$service_price, 2, '.', ''),
                'sub_total' => number_format((float)$sub_total, 2, '.', ''),
                'vehicle_price' => number_format((float)$vehicle_price, 2, '.', ''),
                'insurance_price' => number_format((float)$insurance_value, 2, '.', ''), // 'insurance_price'
                'tax_price' => number_format((float)$tax_price, 2, '.', ''),
                'helper_fee' => number_format((float)$helper_fee, 2, '.', ''),
                'total_price' => number_format((float)$amountToPay, 2, '.', ''),
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);

            // if unable to create deliveryBooking then rollback booking
            if (!$deliveryBooking) {
                $booking->delete();
                return response()->json(['success' => false, 'data' => 'Unable to create booking']);
            }
        }


        if ($serviceType->type == 'moving') {
            // Create Booking Payment
            $movingBooking = BookingMoving::create([
                'booking_id' => $booking->id,
                'service_price' => number_format((float)$service_price, 2, '.', ''),
                'distance_price' => number_format((float)$distance_price, 2, '.', ''),
                'floor_assess_price' => number_format((float)$floor_assess_price, 2, '.', ''),
                'floor_plan_price' => number_format((float)$floor_plan_price, 2, '.', ''),
                'job_details_price' => number_format((float)$job_details_price, 2, '.', ''),
                'no_of_room_price' => number_format((float)$no_of_room_price, 2, '.', ''),
                'priority_price' => number_format((float)$priority_price, 2, '.', ''),
                'weight_price' => number_format((float)$weight_price, 2, '.', ''),
                'sub_total' => number_format((float)$sub_total, 2, '.', ''),
                'tax_price' => number_format((float)$tax_price, 2, '.', ''),
                'helper_fee' => number_format((float)$helper_fee, 2, '.', ''),
                'total_price' => number_format((float)$amountToPay, 2, '.', ''),
                'payment_method' => 'cod',
                'payment_status' => 'unpaid',
            ]);

            // if unable to create movingBooking then rollback booking
            if (!$movingBooking) {
                $booking->delete();
                return response()->json(['success' => false, 'data' => 'Unable to create booking']);
            }
        }


        // After successful booking. Store address book for later use
        // Data to store
        $addressBookData = [
            'user_id' => auth()->user()->id,
            'client_id' => $client->id,
            'pickup_address' => $booking->pickup_address ?? null,
            'dropoff_address' => $booking->dropoff_address ?? null,
            'pickup_latitude' => $booking->pickup_latitude ?? null,
            'pickup_longitude' => $booking->pickup_longitude ?? null,
            'dropoff_latitude' => $booking->dropoff_latitude ?? null,
            'dropoff_longitude' => $booking->dropoff_longitude ?? null,
            'receiver_name' => $booking->receiver_name ?? null,
            'receiver_phone' => $booking->receiver_phone ?? null,
            'receiver_email' => $booking->receiver_email ?? null,
        ];

        // Check if addressBook already exist with same data
        $addressBook = AddressBook::where($addressBookData)->first();
        if (!$addressBook) {
            $addressBook = AddressBook::create($addressBookData);
        }

        // User Notification
        $userNofitication = UserNotification::create([
            'sender_user_id' => null,
            'receiver_user_id' => auth()->user()->id,
            'receiver_user_type' => 'client',
            'type' => 'booking',
            'reference_id' => $booking->id,
            'title' => 'New Booking',
            'content' => 'You have successfully created booking for ' . $serviceType->name . ' service',
            'read' => 0
        ]);

        // Response json with success
        return response()->json(['success' => true, 'data' => $booking, 'message' => 'Booking created successfully'], 201);
    }

    public function store_backup(Request $request)
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

        // Generate uuid
        $uuid = Str::random(8);

        // Generate uuid and ensure it is unique
        do {
            $uuid = Str::random(8);
            $booking = Booking::where('uuid', $uuid)->first();
        } while ($booking);

        // String uuid
        $request->request->add([
            'uuid' => $uuid,
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
            if ($request->distance > $service_category->base_distance) {
                $distance_price =  ($request->distance - $service_category->base_distance) * $service_category->extra_distance_price;
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

        //  Tax Price
        $tax_price = 0;
        $taxPercentage = 0;

        // check if auth exist
        if (Auth::check()) {
            $client = Client::where('user_id', Auth::user()->id)->first();
            if ($client) {
                // Check if client is company or individual
                if ($client->company_enabled) {
                    $taxPercentage = $this->getClientCompanyTax();
                } else {
                    $taxPercentage = $this->getClientTax();
                }
                $tax_price = $taxPercentage;
            }
        }

        $sub_total = $service_category->base_price + $distance_price + $priority_price + $vehicle_price + $weight_price;


        if ($taxPercentage > 0) {
            $tax_price = $sub_total * ($taxPercentage / 100);
        }

        // helper_fee
        $helper_fee = $service_category->helper_fee;

        // Total amountToPay
        $amountToPay = $service_category->base_price + $distance_price + $priority_price + $vehicle_price + $weight_price + $tax_price;

        // Create Booking Payment
        $paymentBooking = BookingDelivery::create([
            'booking_id' => $booking->id,
            'distance_price' => number_format((float)$distance_price, 2, '.', ''),
            'weight_price' => number_format((float)$weight_price, 2, '.', ''),
            'priority_price' => number_format((float)$priority_price, 2, '.', ''),
            'service_price' => number_format((float)$service_category->base_price, 2, '.', ''),
            'sub_total' => number_format((float)$sub_total, 2, '.', ''),
            'vehicle_price' => number_format((float)$vehicle_price, 2, '.', ''),
            'tax_price' => number_format((float)$tax_price, 2, '.', ''),
            'helper_fee' => number_format((float)$helper_fee, 2, '.', ''),
            'total_price' => number_format((float)$amountToPay, 2, '.', ''),
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ]);

        // if unable to create paymentBooking then rollback booking
        if (!$paymentBooking) {
            $booking->delete();
            return response()->json(['success' => false, 'data' => 'Unable to create booking']);
        }


        // After successful booking. Store address book for later use
        // Data to store
        $addressBookData = [
            'user_id' => auth()->user()->id,
            'client_id' => $client->id,
            'pickup_address' => $booking->pickup_address ?? null,
            'dropoff_address' => $booking->dropoff_address ?? null,
            'pickup_latitude' => $booking->pickup_latitude ?? null,
            'pickup_longitude' => $booking->pickup_longitude ?? null,
            'dropoff_latitude' => $booking->dropoff_latitude ?? null,
            'dropoff_longitude' => $booking->dropoff_longitude ?? null,
            'receiver_name' => $booking->receiver_name ?? null,
            'receiver_phone' => $booking->receiver_phone ?? null,
            'receiver_email' => $booking->receiver_email ?? null,
        ];

        // Check if addressBook already exist with same data
        $addressBook = AddressBook::where($addressBookData)->first();
        if (!$addressBook) {
            $addressBook = AddressBook::create($addressBookData);
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
        $cod_enabled = PaymentSetting::where('key', 'cod_enabled')->first();
        $paypal_enabled = PaymentSetting::where('key', 'paypal_enabled')->first();
        $stripe_enabled = PaymentSetting::where('key', 'stripe_enabled')->first();

        // Stripe publishable key
        $stripe_publishable_key_row = PaymentSetting::where('key', 'stripe_publishable_key')->first();
        if ($stripe_publishable_key_row) {
            $stripe_publishable_key = $stripe_publishable_key_row->value;
        } else {
            $stripe_publishable_key = null;
        }

        // $stripe_secret_key = PaymentSetting::where('key', 'stripe_secret_key')->first();

        // COD Enabled
        $codEnabled = false;
        if (isset($cod_enabled) && $cod_enabled->value == 'yes') {
            $codEnabled = true;
        }
        // dd($cod_enabled->value);

        // Paypal Enabled
        $paypalEnabled = false;
        if (isset($paypal_enabled) && $paypal_enabled->value == 'yes') {
            $paypalEnabled = true;
        }

        // Stripe Enabled
        $stripeEnabled = false;
        if (isset($stripe_enabled) && $stripe_enabled->value == 'yes') {
            $stripeEnabled = true;
        }

        // dd($paypalEnabled, $stripeEnabled);

        $bookingData = null;

        // Getting booking delivery data
        if ($booking->booking_type == 'delivery') {
            $bookingData = BookingDelivery::where('booking_id', $booking->id)->first();
        }

        // get booking moving
        if ($booking->booking_type == 'moving') {
            $bookingData = BookingMoving::where('booking_id', $booking->id)->first();
        }
        // dd($bookingDelivery);

        return view('frontend.payment_booking', compact('booking', 'bookingData', 'paypalEnabled', 'stripeEnabled', 'codEnabled', 'stripe_publishable_key'));
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
            if ($booking->booking_type == 'delivery') {
                BookingDelivery::where('booking_id', $booking->id)->update(['transaction_id' =>  $paymentDetails['id'], 'payment_status' => 'paid', 'payment_method' => 'paypal', 'payment_at' => Carbon::now()]);
            }

            if ($booking->booking_type == 'moving') {
                BookingMoving::where('booking_id', $booking->id)->update(['transaction_id' =>  $paymentDetails['id'], 'payment_status' => 'paid', 'payment_method' => 'paypal', 'payment_at' => Carbon::now()]);
            }

            // Send notification to user
            $userNofitication = UserNotification::create([
                'sender_user_id' => null,
                'receiver_user_id' => auth()->user()->id,
                'receiver_user_type' => 'client',
                'type' => 'booking',
                'reference_id' => $booking->id,
                'title' => 'Booking Payment',
                'content' => 'You have successfully paid for your booking',
                'read' => 0
            ]);

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

        // Handle payment cancellation
        return redirect()->back()->with('error', 'Payment cancelled');
    }

    // chargeStripePayment

    public function chargeStripePayment(Request $request)
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

        // Get Stripe Client ID from payment settings
        $stripe_publishable_key = PaymentSetting::where('key', 'stripe_publishable_key')->first();
        if (!$stripe_publishable_key) {
            return redirect()->back()->with('error', 'Stripe publishable key not found');
        }

        $stripe_publishable_key = $stripe_publishable_key->value;

        // Get stripe_secret_key from payment settings
        $stripe_secret_key = PaymentSetting::where('key', 'stripe_secret_key')->first();
        if (!$stripe_secret_key) {
            return redirect()->back()->with('error', 'Stripe secret key not found');
        }

        // Set your Stripe API key.
        \Stripe\Stripe::setApiKey($stripe_publishable_key);

        // Get the payment amount and email address from the form.
        $amount = $booking->total_price * 100;
        $email = auth()->user()->email;

        // Create a new Stripe customer.
        $customer = \Stripe\Customer::create([
            'email' => $email,
            'source' => $request->input('stripeToken'),
        ]);

        // Create a new Stripe charge.
        $charge = \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $amount,
            'currency' => 'usd',
        ]);

        dd($charge);

        // Display a success message to the user.
        return 'Payment successful!';
    }

    // Make COD Payment
    public function codPayment(Request $request)
    {

        // Check if booking exist on id
        $booking = Booking::find($request->id);

        if (!$booking) {
            return response()->json(['success' => false, 'data' => 'Unable to find booking']);
        }

        // Check if current user is booked by this booking
        if ($booking->client_user_id != auth()->user()->id) {
            return response()->json(['success' => false, 'data' => 'Unable to find booking']);
        }

        if ($booking->booking_type == 'delivery') {
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();

            if ($bookingDelivery->payment_status == 'paid') {
                return response()->json(['success' => false, 'data' => 'Booking already paid']);
            }
        }

        if ($booking->booking_type == 'moving') {
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();

            if ($bookingMoving->payment_status == 'paid') {
                return response()->json(['success' => false, 'data' => 'Booking already paid']);
            }
        }

        // dd($booking);

        // Update booking to paid status

        $booking->update([
            'status' => 'pending',
        ]);

        if ($booking->booking_type == 'delivery') {
            $bookingDelivery->update([
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'payment_at' => Carbon::now(),
            ]);
        }

        if ($booking->booking_type == 'moving') {
            $bookingMoving->update([
                'payment_method' => 'cod',
                'payment_status' => 'paid',
                'payment_at' => Carbon::now(),
            ]);
        }

        // Send notification to user

        $userNofitication = UserNotification::create([
            'sender_user_id' => null,
            'receiver_user_id' => auth()->user()->id,
            'receiver_user_type' => 'client',
            'type' => 'booking',
            'reference_id' => $booking->id,
            'title' => 'Booking Payment',
            'content' => 'You have successfully paid for your booking',
            'read' => 0
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

        // Client view true
        $clientView = true;

        // Helper view false
        $helperView = false;

        if ($booking->booking_type == 'delivery') {
            // Getting booking payment data
            $bookingPayment = BookingDelivery::where('booking_id', $booking->id)->first();
        }

        if ($booking->booking_type == 'moving') {
            $bookingPayment = BookingMoving::where('booking_id', $booking->id)->first();
        }

        $booking->currentStatus = 1;
        // switch to manage booking status
        switch ($booking->status) {
            case 'pending':
                $booking->currentStatus = 0;
                break;
            case 'accepted':
                $booking->currentStatus = 1;
                break;
            case 'started':
                $booking->currentStatus = 2;
                break;
            case 'in_transit':
                $booking->currentStatus = 3;
                break;
            case 'completed':
                $booking->currentStatus = 4;
                break;
            case 'incomplete':
                $booking->currentStatus = 5;
                break;
            default:
                $booking->currentStatus = 1;
                break;
        }

        $booking->moverCount = 0;

        if ($booking->helper_user_id !== null) {
            $booking->moverCount++;
        }

        if ($booking->helper_user_id2 !== null) {
            $booking->moverCount++;
        }


        // dd($booking->currentStatus);

        // Get helper Data
        $helperData = null;
        if ($booking->helper_user_id) {
            $helperData = Helper::where('user_id', $booking->helper_user_id)->first();
        }

        // Get helper2 Data
        $helper2Data = null;
        if ($booking->helper_user_id2) {
            $helper2Data = Helper::where('user_id', $booking->helper_user_id2)->first();
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

        // Get helper2 vehicle data
        $helper2VehicleData = null;
        if ($booking->helper_user_id2) {
            $helper2VehicleData = HelperVehicle::where('user_id', $booking->helper_user_id2)->first();
        }

        // Check if invoice already created
        if ($booking->invoice_file == null) {
            // Generate invoice from this url bookingInvoicePDF
            // $this->generateInvoice($booking->id);
            $booking_invoice = $this->getEstimateController->generateInvoice($booking->id);
            if ($booking_invoice) {
                // Update booking
                Booking::where('id', $booking->id)->update([
                    'invoice_file' => $booking_invoice
                ]);
            }
        }

        // Check if label_file already created
        if ($booking->label_file == null) {
            // Generate label from this url bookingLabelPDF
            // $this->generateLabel($booking->id);
            $booking_label = $this->getEstimateController->generateLabel($booking->id);
            if ($booking_label) {
                // Update booking
                Booking::where('id', $booking->id)->update([
                    'label_file' => $booking_label
                ]);
            }
        }

        // Check if review exist for booking
        $review = BookingReview::where('booking_id', $booking->id)->first();

        if ($review) {
            $booking->review = $review;
        }


        // dd($vehicleTypeData);

        return view('frontend.bookings.show', compact('booking', 'bookingPayment', 'helperData', 'helper2Data', 'clientData', 'vehicleTypeData', 'helperVehicleData', 'helper2VehicleData', 'clientView', 'helperView'));
    }

    // Cancel Booking
    public function cancel(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('client_user_id', Auth::user()->id)
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

    // Get client individual tax calculation
    private function getClientTax()
    {
        $taxPercentage = 0;

        // Check if user has added the tax detail
        $clientStateTaxID = Client::where('user_id', Auth::user()->id)->first()->tax_id;

        // If user has not added tax detail then only apply tax
        if (!$clientStateTaxID) {
            // Get client address state
            $clientStateID = Client::where('user_id', Auth::user()->id)->first()->state;
            // $taxPercentage = Auth::user()->id;
            if ($clientStateID) {
                $taxSetting = TaxSetting::where('state_id', $clientStateID)->first();
                if ($taxSetting) {
                    $taxPercentage = $taxSetting->gst_rate + $taxSetting->hst_rate + $taxSetting->pst_rate;
                }
            }
        }

        return $taxPercentage;
    }

    private function getClientCompanyTax()
    {
        $taxPercentage = 0;

        // Check if user has added the tax detail
        $clientStateTaxID = ClientCompany::where('user_id', Auth::user()->id)->first()->tax_id;

        // If user has not added tax detail then only apply tax
        if (!$clientStateTaxID) {
            // Get client address state
            // $clientStateID = Client::where('user_id', Auth::user()->id)->first()->state_id;
            $clientStateID = ClientCompany::where('user_id', Auth::user()->id)->first()->state;
            if ($clientStateID) {
                $taxSetting = TaxSetting::where('state_id', $clientStateID)->first();
                if ($taxSetting) {
                    $taxPercentage = $taxSetting->gst_rate + $taxSetting->hst_rate + $taxSetting->pst_rate;
                }
            }
        }

        return $taxPercentage;
    }
}
