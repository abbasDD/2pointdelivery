<?php

namespace App\Http\Controllers;

use App\Models\Booking;
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
use App\Models\BookingMovingConfig;
use App\Models\BookingMovingDetail;
use App\Models\BookingReview;
use App\Models\BookingSecureship;
use App\Models\BookingSecureshipPackage;
use App\Models\DeliveryConfig;
use App\Models\HelperCompany;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\User;
use App\Models\UserNotification;
use App\Models\UserWallet;
use Illuminate\Support\Facades\Log;

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

        $bookings = Booking::where('client_user_id', Auth::user()->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->orderBy('bookings.updated_at', 'desc')->get();

        // Get booking details
        foreach ($bookings as $booking) {
            if ($booking->helper_user_id != NULL) {
                $booking->helper = Helper::where('user_id', $booking->helper_user_id)->first();
            }

            $booking->client = Client::where('user_id', $booking->client_user_id)->first();

            $booking->moving = null;

            if ($booking->booking_type == 'delivery') {
                $booking->delivery = BookingDelivery::where('booking_id', $booking->id)->first();
            }

            // Check the payment_method of booking
            switch ($booking->booking_type) {
                case 'moving':
                    $booking->moving = BookingMoving::where('booking_id', $booking->id)->first() ?? null;
                    $booking->payment_method = $booking->moving->payment_method ?? null;
                    break;
                case 'delivery':
                    $booking->delivery = BookingDelivery::where('booking_id', $booking->id)->first() ?? null;
                    $booking->payment_method = $booking->delivery->payment_method ?? null;
                    break;
                default:
                    $booking->payment_method = 'cod';
                    break;
            }

            // Check if booking is refunded or not
            $booking->refunded = UserWallet::where('booking_id', $booking->id)->where('user_id', Auth::user()->id)->where('user_type', 'client')->where('type', 'refund')->first() ? true : false;
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
            'selectedServiceTypeID' => 'required|integer|exists:service_types,id',
            'priorityID' => 'required|integer|exists:priority_settings,id',
            'selectedServiceCategoryUuid' => 'required|string|exists:service_categories,uuid',
            'pickup_address' => 'required|string|max:255',
            'dropoff_address' => 'required|string|max:255',
            'pickup_latitude' => 'required|string|max:255',
            'pickup_longitude' => 'required|string|max:255',
            'dropoff_latitude' => 'required|string|max:255',
            'dropoff_longitude' => 'required|string|max:255',
            'booking_date' => 'required|string|max:255',
            'booking_time' => 'required|string|max:255',
            'selectedServiceType' => 'required|string|max:255',
            'total_price' => 'nullable|string|max:255',
            'base_price' => 'nullable|string|max:255',
            'distance' => 'nullable|string|max:255',
            'base_distance' => 'nullable|string|max:255',
            'extra_distance_price' => 'nullable|string|max:255',
            'weight' => 'nullable|string|max:255',
            'base_weight' => 'nullable|string|max:255',
            'extra_weight_price' => 'nullable|string|max:255',
            'selectedSecureshipService' => 'nullable|string|max:255',
        ]);

        // Check if service type available for booking
        $serviceType = ServiceType::where('id', $request->selectedServiceTypeID)->where('is_active', 1)->first();
        if ($serviceType) {
            $request->request->add(['service_type_id' => $serviceType->id]);
        }

        // Get service_category_id from uuid
        $serviceCategory = ServiceCategory::where('uuid', $request->selectedServiceCategoryUuid)->first();
        if ($serviceCategory) {
            $request->request->add(['service_category_id' => $serviceCategory->id]);
        }

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json(['success' => false, 'errors' => $validator->errors()]);
        }

        // priorityID
        if (isset($request->priorityID)) {
            $request->request->add(['priority_setting_id' => $request->priorityID]);
        }

        // Generate numeric uuid
        $uuid = random_int(10000000, 99999999);

        // Generate uuid and ensure it is unique
        do {
            $uuid = random_int(10000000, 99999999);
            $booking = Booking::where('uuid', $uuid)->first();
        } while ($booking);

        // String uuid
        $request->request->add([
            'uuid' => $uuid,
        ]);

        // Add client_user_id
        $client = Client::where('user_id', Auth::user()->id)->first();

        // if not found then create
        if (!$client) {
            $newClient = Client::create([
                'user_id' => Auth::user()->id,
            ]);
            $client = Client::where('user_id', Auth::user()->id)->first();
        }

        // Check booking type
        $booking_type = $this->checkBookingType($serviceType, $serviceCategory);

        // Check if Secureship is enabled
        if ($serviceCategory->is_secureship_enabled) {

            // Call Secureship API function
            $secureshipDataResponse = $this->getEstimateController->getSecureshipEstimate($request);

            if ($secureshipDataResponse['status'] == 'error') {
                return response()->json([
                    'success' => false,
                    'message' => 'Secureship API error',
                ]);
            }

            if (count($secureshipDataResponse['data']) == 0) {
                return response()->json([
                    'success' => false,
                    'message' => 'Secureship API error',
                ]);
            }

            $secureshipData = $secureshipDataResponse['data'];
        }

        // update date format to this 2024-08-20
        $booking_date = date('Y-m-d', strtotime($request->booking_date));

        // Change booking_time to 05:39:00
        $request->request->add([
            'booking_time' => date('H:i:s', strtotime($request->booking_time)),
        ]);

        // Create new booking
        $booking = Booking::create([
            'uuid' => $uuid,
            'client_user_id' => Auth::user()->id,
            'service_type_id' => $request->selectedServiceTypeID,
            'service_category_id' => $serviceCategory->id,
            'priority_setting_id' => $request->priorityID,
            'booking_type' => $booking_type ?? 'delivery',
            'is_secureship_enabled' => $serviceCategory->is_secureship_enabled,
            'pickup_address' => $request->pickup_address,
            'dropoff_address' => $request->dropoff_address,
            'pickup_latitude' => $request->pickup_latitude,
            'pickup_longitude' => $request->pickup_longitude,
            'dropoff_latitude' => $request->dropoff_latitude,
            'dropoff_longitude' => $request->dropoff_longitude,
            'booking_date' => $booking_date,
            'booking_time' => $request->booking_time,
            'total_price' => 0,
            'booking_at' => now(),
            'receiver_name' => $request->receiver_name,
            'receiver_phone' => $request->receiver_phone,
            'receiver_email' => $request->receiver_email,
            'delivery_note' => $request->delivery_note,
        ]);

        // $distance_in_km = 5;
        $distance_in_km = $this->getEstimateController->getDistanceInKM($request->pickup_latitude, $request->pickup_longitude, $request->dropoff_latitude, $request->dropoff_longitude, 'K');
        // add to request
        $request->request->add([
            'distance_in_km' => $distance_in_km,
        ]);

        // Now we have 3 cases 1. Delivery 2. Moving 3. Secureship
        switch ($booking_type) {
            case 'delivery':
                $response = $this->createDeliveryBooking($request, $booking, $serviceCategory, $booking_type);
                break;
            case 'moving':
                $response = $this->createMovingBooking($request, $booking, $serviceCategory, $booking_type);
                break;
            case 'secureship':
                $response = $this->createSecureshipBooking($request, $booking, $serviceCategory, $secureshipData);
                if ($response['status'] == false) {
                    return response()->json([
                        'status' => 'error',
                        'message' => $response['message'],
                        'data' => $response['data'],
                    ]);
                }
                break;
            default:
                return response()->json([
                    'status' => 'error',
                    'message' => 'Invalid booking type',
                ]);
                break;
        }

        // If response is false
        if (!$response) {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to create booking',
                'data' => $response,
            ]);
        }


        // After successful booking. Store address book for later use
        // Data to store
        $addressBookData = [
            'user_id' => Auth::user()->id,
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

    // Check Booking Type
    private function checkBookingType($serviceType, $serviceCategory)
    {
        if ($serviceType->type == 'moving') {

            return 'moving';
        }

        // Check if secureship is enabled
        if ($serviceCategory->is_secureship_enabled == 1) {
            return 'secureship';
        }

        return 'delivery';
    }

    // createDeliveryBooking
    private function createDeliveryBooking($request, $booking, $serviceCategory, $booking_type)
    {
        // Get priority_price
        $priority_price = 0;
        // Check if priority setting exist
        if (isset($request->priorityID)) {
            $priority_setting = PrioritySetting::find($request->priorityID);
            if ($priority_setting) {
                $priority_price = $priority_setting->price;
            }
        }

        // Calculate prices
        $distance_price = $this->getEstimateController->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $request->distance_in_km);
        $weight_price = $this->getEstimateController->getWeightPrice($booking_type, $serviceCategory, $request->package_weight, $request->package_length, $request->package_width, $request->package_height, $request->selectedMovingDetailsID);
        $service_price = $this->getEstimateController->getBasePrice($booking_type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);
        $vehicle_price = $this->getEstimateController->getVehiclePrice($booking_type, $serviceCategory->vehicle_type_id, $request->distance_in_km);
        $insurance_price = $this->getEstimateController->getInsuranceValue($booking_type, $request->package_value);

        $sub_total = $distance_price + $weight_price + $service_price + $priority_price + $vehicle_price + $insurance_price;


        //  Tax Price
        $tax_price = $this->getEstimateController->getTaxPrice($sub_total);

        // Total Price
        $amountToPay = $sub_total + $tax_price;

        // helper_fee
        $helper_fee = $serviceCategory->helper_fee;

        $deliveryBooking = BookingDelivery::create([
            'booking_id' => $booking->id,
            'distance_price' => number_format((float) $distance_price, 2, '.', ''),
            'weight_price' => number_format((float) $weight_price, 2, '.', ''),
            'priority_price' => number_format((float) $priority_price, 2, '.', ''),
            'service_price' => number_format((float) $service_price, 2, '.', ''),
            'sub_total' => number_format((float) $sub_total, 2, '.', ''),
            'vehicle_price' => number_format((float) $vehicle_price, 2, '.', ''),
            'insurance_price' => number_format((float) $insurance_price, 2, '.', ''), // 'insurance_price'
            'tax_price' => number_format((float) $tax_price, 2, '.', ''),
            'helper_fee' => number_format((float) $helper_fee, 2, '.', ''),
            'total_price' => number_format((float) $amountToPay, 2, '.', ''),
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ]);

        if ($deliveryBooking) {

            // Update booking
            $booking->total_price = number_format((float) $amountToPay, 2, '.', '');
            $booking->booking_type = 'delivery';
            $booking->save();

            return true;
        }

        return false;
    }

    // createMovingBooking
    private function createMovingBooking($request, $booking, $serviceCategory, $booking_type)
    {
        // Get priority_price
        $priority_price = 0;
        // Check if priority setting exist
        if (isset($request->priorityID)) {
            $priority_setting = PrioritySetting::find($request->priorityID);
            if ($priority_setting) {
                $priority_price = $priority_setting->price;
            }
        }

        // Calculate prices
        // Get Base Price Value
        $service_price = $this->getEstimateController->getBasePrice($booking_type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);

        // distance_price
        $distance_price = $this->getEstimateController->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $request->distance_in_km);

        // Get Weight Price
        $weight_price = $this->getEstimateController->getWeightPrice($booking_type, $serviceCategory, $request->package_weight, $request->package_length, $request->package_width, $request->package_height, $request->selectedMovingDetailsID);

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

        // Sub total
        $sub_total = $distance_price + $weight_price + $service_price + $priority_price + $no_of_room_price + $floor_plan_price + $floor_assess_price + $job_details_price;

        //  Tax Price
        $tax_price = $this->getEstimateController->getTaxPrice($sub_total);

        // Total Price
        $amountToPay = $sub_total + $tax_price;

        // helper_fee
        $helper_fee = $serviceCategory->helper_fee;

        // Create Booking Payment
        $movingBooking = BookingMoving::create([
            'booking_id' => $booking->id,
            'service_price' => number_format((float) $service_price, 2, '.', ''),
            'distance_price' => number_format((float) $distance_price, 2, '.', ''),
            'floor_assess_price' => number_format((float) $floor_assess_price, 2, '.', ''),
            'floor_plan_price' => number_format((float) $floor_plan_price, 2, '.', ''),
            'job_details_price' => number_format((float) $job_details_price, 2, '.', ''),
            'no_of_room_price' => number_format((float) $no_of_room_price, 2, '.', ''),
            'priority_price' => number_format((float) $priority_price, 2, '.', ''),
            'weight_price' => number_format((float) $weight_price, 2, '.', ''),
            'sub_total' => number_format((float) $sub_total, 2, '.', ''),
            'tax_price' => number_format((float) $tax_price, 2, '.', ''),
            'helper_fee' => number_format((float) $helper_fee, 2, '.', ''),
            'total_price' => number_format((float) $amountToPay, 2, '.', ''),
            'payment_method' => 'cod',
            'payment_status' => 'unpaid',
        ]);

        if ($movingBooking) {
            // Update booking
            $booking->total_price = number_format((float) $amountToPay, 2, '.', '');
            $booking->booking_type = 'moving';
            $booking->save();

            // Create moving config
            $helper_fee_updated = $this->createBookingMovingConfig($request, $serviceCategory, $booking, $movingBooking);

            // Create booking_moving_details
            $this->createBookingMovingDetails($request, $serviceCategory, $booking, $movingBooking);

            // Update helper fee
            $movingBooking->helper_fee = $helper_fee_updated;
            $movingBooking->save();


            return true;
        }

        return false;
    }

    // createBookingMovingConfig
    public function createBookingMovingConfig($request, $serviceCategory, $booking, $movingBooking)
    {
        // Helper fee as per configs
        $helper_fee = $serviceCategory->helper_fee ?? 0;

        // If no_of_room_enabled for this category then add it to booking moving configs
        if ($serviceCategory->no_of_room_enabled == 1) {
            // Get selected no of room id
            $noOfRoomData = MovingConfig::where('id', $request->selectedNoOfRoomID)->where('type', 'no_of_rooms')->first();
            if ($noOfRoomData) {
                //Create booking_moving_configs
                BookingMovingConfig::create([
                    'booking_id' => $booking->id,
                    'booking_moving_id' => $movingBooking->id,
                    'moving_config_id' => $noOfRoomData->id,
                    'name' => $noOfRoomData->name,
                    'type' => 'no_of_rooms',
                    'price' => $noOfRoomData->price,
                    'helper_fee' => $noOfRoomData->helper_fee,
                ]);

                $helper_fee += $noOfRoomData->helper_fee;
            }
        }

        // if floor_plan_enabled for this category then add it to booking moving configs
        if ($serviceCategory->floor_plan_enabled == 1) {
            // Get selected floor plan id
            $floorPlanData = MovingConfig::where('id', $request->selectedFloorPlanID)->where('type', 'floor_plan')->first();
            if ($floorPlanData) {
                //Create booking_moving_configs
                BookingMovingConfig::create([
                    'booking_id' => $booking->id,
                    'booking_moving_id' => $movingBooking->id,
                    'moving_config_id' => $floorPlanData->id,
                    'name' => $floorPlanData->name,
                    'type' => 'floor_plan',
                    'price' => $floorPlanData->price,
                    'helper_fee' => $floorPlanData->helper_fee,
                ]);

                $helper_fee += $floorPlanData->helper_fee;
            }
        }


        // if floor_assess_enabled for this category then add it to booking moving configs
        if ($serviceCategory->floor_assess_enabled == 1) {
            // Get selected floor assess id
            $floorAssessData = MovingConfig::where('id', $request->selectedFloorAssessID)->where('type', 'floor_assess')->first();
            if ($floorAssessData) {
                //Create booking_moving_configs
                BookingMovingConfig::create([
                    'booking_id' => $booking->id,
                    'booking_moving_id' => $movingBooking->id,
                    'moving_config_id' => $floorAssessData->id,
                    'name' => $floorAssessData->name,
                    'type' => 'floor_assess',
                    'price' => $floorAssessData->price,
                    'helper_fee' => $floorAssessData->helper_fee,
                ]);

                $helper_fee += $floorAssessData->helper_fee;
            }
        }


        // if job_details_enabled for this category then add it to booking moving configs
        if ($serviceCategory->job_details_enabled == 1) {
            // Check if selectedJobDetailsID is array
            if (is_array($request->selectedJobDetailsID)) {
                $selectedJobDetailsIDs = $request->selectedJobDetailsID;
            } else {
                // Split $selectedFloorAssessID into array
                $selectedJobDetailsIDs = explode(',', $request->selectedJobDetailsID);
            }

            // Loop through selectedJobDetailsIDs
            foreach ($selectedJobDetailsIDs as $selectedJobDetailsID) {
                // Get selected no of room id
                $jobDetailsData = MovingConfig::where('uuid', $selectedJobDetailsID)->where('type', 'job_details')->first();
                if ($jobDetailsData) {
                    // Create booking_moving_configs
                    BookingMovingConfig::create([
                        'booking_id' => $booking->id,
                        'booking_moving_id' => $movingBooking->id,
                        'moving_config_id' => $jobDetailsData->id,
                        'name' => $jobDetailsData->name,
                        'type' => 'job_details',
                        'price' => $jobDetailsData->price,
                        'helper_fee' => $jobDetailsData->helper_fee,
                    ]);

                    $helper_fee += $jobDetailsData->helper_fee;
                }
            }
        }

        return $helper_fee;
    }

    // createBookingMovingDetails
    public function createBookingMovingDetails($request, $new_booking, $serviceCategory, $movingBooking)
    {
        // if ($serviceCategory->moving_details_enabled == 0) {
        //     return false;
        // }

        // Check if selectedMovingDetailsID is array
        if (is_array($request->selectedMovingDetailsID)) {
            $selectedMovingDetailsIDs = $request->selectedMovingDetailsID;
        } else {
            $selectedMovingDetailsIDs = explode(',', $request->selectedMovingDetailsID);
        }

        // Get moving booking
        $movingBookingUpdated = BookingMoving::find($movingBooking->id);
        $booking_id = $movingBookingUpdated->booking_id ?? $new_booking->id;

        // Loop through selectedMovingDetailsID
        foreach ($selectedMovingDetailsIDs as $item) {
            // Get from movingdetails
            $movingDetailItem = MovingDetail::where('uuid', $item)->first();
            if ($movingDetailItem) {
                // Create booking moving details
                BookingMovingDetail::create([
                    'booking_id' => $booking_id,
                    'booking_moving_id' => $movingBooking->id,
                    'moving_detail_id' => $movingDetailItem->id,
                    'name' => $movingDetailItem->name ?? 'name',
                    'description' => $movingDetailItem->description ?? null,
                    'weight' => $movingDetailItem->weight ?? 0,
                    'volume' => $movingDetailItem->volume ?? 0,
                ]);
            }
        }

        return true;
    }

    // createSecureshipBooking
    public function createSecureshipBooking($request, $booking, $serviceCategory, $secureshipData)
    {
        if ($serviceCategory->is_secureship_enabled == 0) {
            return ['status' => false, 'message' => 'Secureship is not enabled for this service', 'data' => []];
        }

        $selectedSecureshipService = $request->selectedSecureshipService;

        // Find Secureship Service Details as per selectedSecureshipService from secureshipData
        $selectedServiceDetail = null;

        foreach ($secureshipData as $item) {
            if ($item['selectedService'] === $selectedSecureshipService) {
                $selectedServiceDetail = $item;
                break;
            }
        }

        if (!$selectedServiceDetail) {
            return ['status' => false, 'message' => 'No item found for selected service', 'data' => []];
        }

        // Get pickup_address object from lat long
        $pickup_address = $this->getEstimateController->getAddressFromLatLong($request->pickup_latitude, $request->pickup_longitude);

        if (!$pickup_address) {
            return ['status' => 'error', 'message' => 'Invalid pickup address. Please try again.'];
        }

        // Get dropoffaddress object from lat long
        $dropoff_address = $this->getEstimateController->getAddressFromLatLong($request->dropoff_latitude, $request->dropoff_longitude);
        if (!$dropoff_address) {
            return ['status' => 'error', 'message' => 'Invalid dropoff address. Please try again.'];
        }

        // Calculate $platformCommission
        $platformCommission = 0;

        // Adjust data as per secureship booking table
        $bookingSecureshipTableData = [
            'booking_id' => $booking->id,
            'fromAddress_addr1' => $pickup_address['addr1'],
            'fromAddress_countryCode' => $pickup_address['countryCode'],
            'fromAddress_postalCode' => $pickup_address['postalCode'],
            'fromAddress_city' => $pickup_address['city'],
            'fromAddress_taxId' => null,
            'fromAddress_residential' => false,
            'toAddress_addr1' => $dropoff_address['addr1'],
            'toAddress_countryCode' => $dropoff_address['countryCode'],
            'toAddress_postalCode' => $dropoff_address['postalCode'],
            'toAddress_city' => $dropoff_address['city'],
            'toAddress_taxId' => null,
            'toAddress_residential' => false,
            'billableWeight' => $selectedServiceDetail['billableWeight']['value'],
            'billableWeightUnit' => $selectedServiceDetail['billableWeight']['units'],
            'shipDateTime' => null,
            'currencyCode' => $selectedServiceDetail['currencyCode'],
            'carrierCode' => $selectedServiceDetail['carrierCode'],
            'selectedSecureshipService' => $selectedServiceDetail['selectedService'],
            'serviceName' => $selectedServiceDetail['serviceName'],
            'useSecureship' => $selectedServiceDetail['useSecureship'],
            'rateZone' => $selectedServiceDetail['rateZone'],
            'pickupAvailable' => $selectedServiceDetail['pickupAvailable'],
            'pickupFee' => $selectedServiceDetail['pickupFee'],
            'fuelSurcharge' => $selectedServiceDetail['fuelSurcharge']['amount'],
            'subTotal' => $selectedServiceDetail['subTotal'],
            'taxAmount' => $selectedServiceDetail['taxDetails']['amount'],
            'total' => $selectedServiceDetail['total'],
            'regularPrice' => $selectedServiceDetail['regularPrice'],
            '2pointCommission' => $platformCommission,
            'grandTotal' => $selectedServiceDetail['total'] + $platformCommission,
        ];

        // return [
        //     'status' => false,
        //     'message' => 'Secureship booking created successfully',
        //     'data' => $selectedServiceDetail
        // ];

        $secureshipBooking = BookingSecureship::create($bookingSecureshipTableData);

        if (!$secureshipBooking) {
            return ['status' => false, 'message' => 'Secureship booking not created', 'data' => []];
        }

        // Create Secureship Booking Packages
        $packages = $this->getEstimateController->getSecureshipPackages($request);

        $secureshipBooking->packages()->createMany($packages);

        if ($secureshipBooking) {
            // Update booking
            $booking->total_price = number_format((float) ($selectedServiceDetail['total'] + $platformCommission), 2, '.', '');
            $booking->booking_type = 'secureship';
            $booking->save();
        }


        // Return data
        return [
            'status' => true,
            'message' => 'Secureship booking created successfully',
            'data' => $secureshipBooking
        ];
    }

    // Calculate Booking Time Difference
    public function calculateBookingTimeDifference($booking)
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
        $bookingTimeLeft = (int) (3600 - $timeDifferenceInSeconds);
        // dd($bookingTimeLeft);
        // Convert to minutes and seconds
        // $bookingTimeLeft = (int)($bookingTimeLeft / 60) . ' minutes ' . ($bookingTimeLeft % 60) . ' seconds';

        return $bookingTimeLeft;
    }

    public function payment(Request $request)
    {

        // Check if client completed its profile
        $client = Client::where('user_id', Auth::user()->id)->first();

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
            $companyData = ClientCompany::where('user_id', Auth::user()->id)->first();

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
            ->where('client_user_id', Auth::user()->id)
            ->with('client')
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // If booking status is not in draft
        if ($booking->status != 'draft') {
            return redirect()->back()->with('error', 'Booking already paid');
        }

        $bookingTimeLeft = $this->calculateBookingTimeDifference($booking);
        // dd($bookingTimeLeft);
        // Check if booking time left is greater than 0
        if ($bookingTimeLeft <= 0) {

            $booking->update(['status' => 'expired']);
            return redirect()->back()->with('error', 'Booking already expired');
        }

        // In seconds $bookingTimeLeft to h:i:s
        $bookingTimeLeft = gmdate("H:i:s", $bookingTimeLeft);
        // dd($bookingTimeLeft);
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

        // get booking secureship
        if ($booking->booking_type == 'secureship') {
            $bookingData = BookingSecureship::where('booking_id', $booking->id)->first();
        }
        // dd($bookingDelivery);

        // $distance_in_km = 5;
        $booking->distance_in_km = $this->getEstimateController->getDistanceInKM($booking->pickup_latitude, $booking->pickup_longitude, $booking->dropoff_latitude, $booking->dropoff_longitude, 'K');


        return view('frontend.payment_booking', compact('booking', 'bookingData', 'paypalEnabled', 'stripeEnabled', 'codEnabled', 'stripe_publishable_key', 'bookingTimeLeft'));
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
        $booking = Booking::where('id', $bookingId)->where('client_user_id', Auth::user()->id)->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }
        $booking_uuid = $booking->uuid;

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
                        'currency' => 'CAD',
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
            return redirect()->back()->with('error', 'Invalid request - something went wrong');
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
            $booking->update(['status' => 'pending', 'booking_at' => Carbon::now()]);

            // Update booking payment details
            if ($booking->booking_type == 'delivery') {
                BookingDelivery::where('booking_id', $booking->id)->update(['transaction_id' => $paymentDetails['id'], 'payment_status' => 'paid', 'payment_method' => 'paypal', 'payment_at' => Carbon::now()]);
            }

            if ($booking->booking_type == 'moving') {
                BookingMoving::where('booking_id', $booking->id)->update(['transaction_id' => $paymentDetails['id'], 'payment_status' => 'paid', 'payment_method' => 'paypal', 'payment_at' => Carbon::now()]);
            }

            if ($booking->booking_type == 'secureship') {
                BookingSecureship::where('booking_id', $booking->id)->update(['transaction_id' => $paymentDetails['id'], 'payment_status' => 'paid', 'payment_method' => 'stripe', 'payment_at' => Carbon::now()]);
                // createSecureshipBookingUsingAPI
                $this->createSecureshipBookingUsingAPI($booking_uuid);
            }

            // Add to User Wallet as Paypal Amount
            UserWallet::create([
                'user_id' => Auth::user()->id,
                'user_type' => 'client',
                'type' => 'spend',
                'amount' => $booking->total_price,
                'booking_id' => $booking->id,
                'note' => 'Payment for booking ID: ' . $booking->id,
                'payment_method' => 'paypal',
                'transaction_id' => $paymentDetails['id'],
                'status' => 'success',
                'paid_at' => Carbon::now()
            ]);

            $serviceTypeName = 'Delivery';
            $serviceType = ServiceType::find($booking->service_type_id);
            if ($serviceType) {
                $serviceTypeName = $serviceType->name;
            }

            // Call notificaion client to send notification
            app('notificationHelper')->sendNotification(null, Auth::user()->id, 'client', 'booking', $booking->id, 'New Booking', 'You have successfully created booking for ' . $serviceTypeName . ' service');

            // Send notification to Admin
            app('notificationHelper')->sendNotification(null, 1, 'admin', 'booking', $booking->id, 'New Booking', 'A new booking has been created for ' . $serviceTypeName . ' service');


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
        $booking = Booking::where('id', $bookingId)->where('client_user_id', Auth::user()->id)->first();
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }
        $booking_uuid = $booking->uuid;

        // Get Stripe Client ID from payment settings
        $stripe_publishable_key = PaymentSetting::where('key', 'stripe_publishable_key')->first();
        if (!$stripe_publishable_key) {
            return redirect()->back()->with('error', 'Stripe publishable key not found');
        }

        $stripe_publishable_key = $stripe_publishable_key->value;

        // Get stripe_secret_key from payment settings
        $stripe_secret_key = PaymentSetting::where('key', 'stripe_secret_key')->first()->value ?? null;
        if (!$stripe_secret_key) {
            return redirect()->back()->with('error', 'Stripe secret key not found');
        }

        \Stripe\Stripe::setApiKey($stripe_secret_key);

        // Get the payment amount and email address from the form.
        $amount = $booking->total_price * 100;
        $email = Auth::user()->email;

        // Create a new Stripe customer.
        $customer = \Stripe\Customer::create([
            'email' => $email,
            'source' => $request->input('stripeToken'),
        ]);

        // Create a new Stripe charge.
        $charge = \Stripe\Charge::create([
            'customer' => $customer->id,
            'amount' => $amount,
            'currency' => 'cad',
        ]);

        // dd($charge);

        // Check if the charge was successful.
        if ($charge->status !== 'succeeded') {
            return 'Payment failed';
        }

        // Update booking status
        $booking->update(['status' => 'pending', 'booking_at' => Carbon::now()]);

        // Update booking payment details
        if ($booking->booking_type == 'delivery') {
            BookingDelivery::where('booking_id', $booking->id)->update(['transaction_id' => $charge->id, 'payment_status' => 'paid', 'payment_method' => 'stripe', 'payment_at' => Carbon::now()]);
        }

        if ($booking->booking_type == 'moving') {
            BookingMoving::where('booking_id', $booking->id)->update(['transaction_id' => $charge->id, 'payment_status' => 'paid', 'payment_method' => 'stripe', 'payment_at' => Carbon::now()]);
        }

        if ($booking->booking_type == 'secureship') {
            BookingSecureship::where('booking_id', $booking->id)->update(['transaction_id' => $charge->id, 'payment_status' => 'paid', 'payment_method' => 'stripe', 'payment_at' => Carbon::now()]);
            // createSecureshipBookingUsingAPI
            $this->createSecureshipBookingUsingAPI($booking_uuid);
        }

        // Add to User Wallet as Paypal Amount
        UserWallet::create([
            'user_id' => Auth::user()->id,
            'user_type' => 'client',
            'type' => 'spend',
            'amount' => $booking->total_price,
            'booking_id' => $booking->id,
            'note' => 'Payment for booking ID: ' . $booking->id,
            'payment_method' => 'stripe',
            'transaction_id' => $charge->id,
            'status' => 'success',
            'paid_at' => Carbon::now()
        ]);

        $serviceTypeName = 'Delivery';
        $serviceType = ServiceType::find($booking->service_type_id);
        if ($serviceType) {
            $serviceTypeName = $serviceType->name;
        }

        // Call notificaion client to send notification
        app('notificationHelper')->sendNotification(null, Auth::user()->id, 'client', 'booking', $booking->id, 'New Booking', 'You have successfully created booking for ' . $serviceTypeName . ' service');

        // Send notification to Admin
        app('notificationHelper')->sendNotification(null, 1, 'admin', 'booking', $booking->id, 'New Booking', 'A new booking has been created for ' . $serviceTypeName . ' service');


        // Display a success message to the user.
        // return 'Payment successful!';
        // Redirect to booking detail page
        // return redirect()->route('client.booking.show', $booking->id);
        return redirect()->route('client.bookings')->with('success', 'Payment successful!');
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
        if ($booking->client_user_id != Auth::user()->id) {
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

        if ($booking->booking_type == 'secureship') {
            return response()->json(['success' => false, 'data' => 'COD not allowed for secure ship']);
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

        // Add to User Wallet as COD
        UserWallet::create([
            'user_id' => Auth::user()->id,
            'user_type' => 'client',
            'type' => 'spend',
            'amount' => $booking->total_price,
            'booking_id' => $booking->id,
            'note' => 'Payment for booking ID: ' . $booking->id,
            'payment_method' => 'cod',
            'status' => 'pending',
        ]);

        $serviceTypeName = 'Delivery';
        $serviceType = ServiceType::find($booking->service_type_id);
        if ($serviceType) {
            $serviceTypeName = $serviceType->name;
        }

        // Call notificaion client to send notification
        app('notificationHelper')->sendNotification(null, Auth::user()->id, 'client', 'booking', $booking->id, 'New Booking', 'You have successfully created booking for ' . $serviceTypeName . ' service');

        // Send notification to Admin
        app('notificationHelper')->sendNotification(null, 1, 'admin', 'booking', $booking->id, 'New Booking', 'A new booking has been created for ' . $serviceTypeName . ' service');

        return response()->json(['success' => true, 'data' => 'Booking paid successfully']);
    }

    // createSecureshipBookingUsingAPI
    public function createSecureshipBookingUsingAPI($booking_uuid)
    {
        // Get booking
        $booking = Booking::where('uuid', $booking_uuid)->first();
        if (!$booking) {
            return ['success' => false, 'data' => 'Booking not found'];
        }

        // Get booking secureship
        $bookingSecureship = BookingSecureship::where('booking_id', $booking->id)->first();
        if (!$bookingSecureship) {
            return ['success' => false, 'data' => 'Booking Secureship not found'];
        }

        // Get secureship booking packages
        $secureshipBookingPackages = BookingSecureshipPackage::where('booking_secureship_id', $bookingSecureship->id)->get();
        if ($secureshipBookingPackages->isEmpty()) {
            return ['success' => false, 'data' => 'Secureship booking packages not found'];
        }

        // Convert package data to match API expectations
        $packages = $secureshipBookingPackages->map(function ($package) {
            return [
                'packageType' => $package->packageType,
                'userDefinedPackageType' => $package->userDefinedPackageType,
                'weight' => (float) $package->weight,
                'weightUnits' => $package->weightUnits,
                'length' => (float) $package->length,
                'width' => (float) $package->width,
                'height' => (float) $package->height,
                'dimUnits' => $package->dimUnits,
                'insurance' => (float) $package->insurance,
                'isAdditionalHandling' => filter_var($package->isAdditionalHandling, FILTER_VALIDATE_BOOLEAN),
                'signatureOptions' => $package->signatureOptions,
                'description' => $package->description,
                'temperatureProtection' => filter_var($package->temperatureProtection, FILTER_VALIDATE_BOOLEAN),
                'isDangerousGoods' => filter_var($package->isDangerousGoods, FILTER_VALIDATE_BOOLEAN),
                'isNonStackable' => filter_var($package->isNonStackable, FILTER_VALIDATE_BOOLEAN),
            ];
        })->toArray();

        $payload = [
            'selectedSecureshipService' => $bookingSecureship->selectedSecureshipService,
            'request' => 'createLabel', // Add the necessary request data here
            'fromAddress' => [
                'company' => '2 Point Delivery',
                'contact' => 'Alex Tailor',
                'phone' => '+1 613 714 0729 ext. 12345',
                'addr1' => $bookingSecureship->fromAddress_addr1,
                'addr2' => $bookingSecureship->fromAddress_addr2,
                'addr3' => $bookingSecureship->fromAddress_addr3,
                'countryCode' => $bookingSecureship->fromAddress_countryCode,
                'postalCode' => $bookingSecureship->fromAddress_postalCode,
                'city' => $bookingSecureship->fromAddress_city,
                'province' => $bookingSecureship->fromAddress_province,
                'residential' => filter_var($bookingSecureship->fromAddress_residential, FILTER_VALIDATE_BOOLEAN),
                'taxId' => $bookingSecureship->fromAddress_taxId,
                'emails' => ['2pointdelivery@gmail.com', Auth::user()->email],
                'isInside' => filter_var($bookingSecureship->fromAddress_isInside, FILTER_VALIDATE_BOOLEAN),
                'isTailGate' => filter_var($bookingSecureship->fromAddress_isTailGate, FILTER_VALIDATE_BOOLEAN),
                'isTradeShow' => filter_var($bookingSecureship->fromAddress_isTradeShow, FILTER_VALIDATE_BOOLEAN),
                'isLimitedAccess' => filter_var($bookingSecureship->fromAddress_isLimitedAccess, FILTER_VALIDATE_BOOLEAN),
                'isSaturday' => filter_var($bookingSecureship->fromAddress_isSaturday, FILTER_VALIDATE_BOOLEAN),
                'appointment' => [
                    'appointmentType' => 'None',
                    'phone' => '613-723-5891',
                    'date' => now()->format('Y-m-d'),
                    'time' => now()->format('H:i:s'),
                ]
            ],
            'toAddress' => [
                'company' => '2 Point Delivery',
                'contact' => 'Alex Tailor',
                'phone' => '+1 613 714 0729 ext. 12345',
                'addr1' => $bookingSecureship->toAddress_addr1,
                'addr2' => $bookingSecureship->toAddress_addr2,
                'addr3' => $bookingSecureship->toAddress_addr3,
                'countryCode' => $bookingSecureship->toAddress_countryCode,
                'postalCode' => $bookingSecureship->toAddress_postalCode,
                'city' => $bookingSecureship->toAddress_city,
                'province' => $bookingSecureship->toAddress_province,
                'residential' => filter_var($bookingSecureship->toAddress_residential, FILTER_VALIDATE_BOOLEAN),
                'taxId' => $bookingSecureship->toAddress_taxId,
                'emails' => ['2pointdelivery@gmail.com', Auth::user()->email],
                'isInside' => filter_var($bookingSecureship->toAddress_isInside, FILTER_VALIDATE_BOOLEAN),
                'isTailGate' => filter_var($bookingSecureship->toAddress_isTailGate, FILTER_VALIDATE_BOOLEAN),
                'isTradeShow' => filter_var($bookingSecureship->toAddress_isTradeShow, FILTER_VALIDATE_BOOLEAN),
                'isLimitedAccess' => filter_var($bookingSecureship->toAddress_isLimitedAccess, FILTER_VALIDATE_BOOLEAN),
                'isSaturday' => filter_var($bookingSecureship->toAddress_isSaturday, FILTER_VALIDATE_BOOLEAN),
                'appointment' => [
                    'appointmentType' => 'None',
                    'phone' => '613-723-5891',
                    'date' => now()->addDays(3)->format('Y-m-d'),
                    'time' => now()->format('H:i:s'),
                ]
            ],
            'packages' => $packages,
            'shipDateTime' => Carbon::now()->toIso8601String(),
            'deliveryEmails' => ['2pointdelivery@gmail.com', Auth::user()->email],
            'commercialInvoice' => null,  // Add the commercial invoice if necessary
            'billingOption' => 'Prepaid',
            'billingAccountNumber' => 'AB12345XYZ',
            'documentsOnly' => false,
            'isNonStackable' => true,
            'isStopinOnly' => true,
            'ecommerce' => null,  // Add ecommerce details if necessary
            'references' => ["uuid: {$booking->uuid}", "description: shipping supplies"],
            'comments' => 'Delivery created on 2 Point Delivery app',
            'clearInProgress' => true,
            'editTrackingNumber' => $booking->uuid,
        ];

        // Get secureship API key
        $secureship_api_key = DeliveryConfig::where('key', 'secureship_api_key')->first();
        if (!$secureship_api_key) {
            return [
                'status' => 'error',
                'message' => 'Secureship API key not found',
            ];
        }

        // API URL
        $apiUrl = 'https://secureship.ca/ship/api/v1/carriers/create-label';

        // Make the API request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $secureship_api_key->value,
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            return ['status' => 'success', 'data' => $response->json()];
        } else {
            return response()->json([
                'status' => 'error',
                'message' => 'Failed to retrieve estimate',
                'error' => $response->json(),
            ]);
        }
    }



    /**
     * Display the specified resource.
     */
    public function show(Request $request)
    {
        $booking = Booking::where('id', $request->id)
            ->where('client_user_id', Auth::user()->id)
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        // Check if booking status is draft
        if ($booking->status == 'draft') {
            $bookingTimeLeft = $this->calculateBookingTimeDifference($booking);

            // Check if booking time left is greater than 0
            if ($bookingTimeLeft <= 0) {

                $booking->update(['status' => 'expired']);
                return redirect()->back()->with('error', 'Booking already expired');
            }
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

        if ($booking->booking_type == 'secureship') {
            $bookingPayment = BookingSecureship::where('booking_id', $booking->id)->first();
        }

        // if bookingPayment not found
        if (!$bookingPayment) {
            return redirect()->back()->with('error', 'Booking not found');
        }

        $booking->currentStatus = 1;
        // switch to manage booking status
        switch ($booking->status) {
            case 'pending':
                $booking->currentStatus = 0;
                break;
            case 'cancelled':
                $booking->currentStatus = 1;
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
                $booking->currentStatus = 4;
                break;
            case 'expired':
                $booking->currentStatus = 1;
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
        $helperData2 = null;
        if ($booking->helper_user_id2) {
            $helperData2 = Helper::where('user_id', $booking->helper_user_id2)->first();
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

        // Check if review exist for booking
        $review = BookingReview::where('booking_id', $booking->id)->first();

        if ($review) {
            $booking->review = $review;
        }

        // booking Moving Configs
        $booking_configs = BookingMovingConfig::where('booking_id', $booking->id)->where('type', 'job_details')->get() ?? [];

        // Booking Moving Details
        $booking_moving_details = BookingMovingDetail::where('booking_id', $booking->id)->get() ?? [];

        // dd($vehicleTypeData);

        return view('client.bookings.show', compact('booking', 'bookingPayment', 'helperData', 'helperData2', 'clientData', 'vehicleTypeData', 'helperVehicleData', 'helper2VehicleData', 'clientView', 'helperView', 'booking_configs', 'booking_moving_details'));
    }

    // Generate Booking Invoice
    public function generateInvoice($booking_id)
    {

        $booking = Booking::where('id', $booking_id)->first();

        // Check if invoice already created
        if ($booking->invoice_file == null) {
            $booking_invoice = $this->getEstimateController->generateInvoice($booking->id);
            if ($booking_invoice) {
                // Update booking
                Booking::where('id', $booking->id)->update([
                    'invoice_file' => $booking_invoice
                ]);
            }
        }

        $path = 'pdfs/invoices/' . $booking->invoice_file;
        $url = asset($path);

        return redirect()->away($url);
    }

    // Geenerate Label
    public function generateLabel($booking_id)
    {
        $booking = Booking::where('id', $booking_id)->first();

        // Check if label_file already created
        // if ($booking->label_file == null) {
        $booking_label = $this->getEstimateController->generateLabel($booking->id);
        if ($booking_label) {
            // Update booking
            Booking::where('id', $booking->id)->update([
                'label_file' => $booking_label
            ]);
        }
        // }

        $path = 'pdfs/shipping-labels/' . $booking->booking_label;
        $url = asset($path);

        return redirect()->away($url);
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


    // Get Booking Current Status
    public function getBookingCurrentStatus($booking_status)
    {

        if (!isset($booking_status)) {
            return -1;
        }

        // Set default to -1
        $currentStatus = -1;

        switch ($booking_status) {
            case 'pending':
                $currentStatus = 0;
                break;
            case 'cancelled':
                $currentStatus = 1;
                break;
            case 'accepted':
                $currentStatus = 1;
                break;
            case 'started':
                $currentStatus = 2;
                break;
            case 'in_transit':
                $currentStatus = 3;
                break;
            case 'completed':
                $currentStatus = 4;
                break;
            case 'incomplete':
                $currentStatus = 4;
                break;
            case 'expired':
                $currentStatus = 1;
                break;
            default:
                $currentStatus = 1;
                break;
        }

        return $currentStatus;
    }

    // Get boooking data as per the booking type
    public function getBookingTypeData($booking_type, $booking_id)
    {
        if (!isset($booking_type)) {
            return -1;
        }

        // default to null
        $bookingData = null;

        // Getting booking delivery data
        if ($booking_type == 'delivery') {
            $bookingData = BookingDelivery::where('booking_id', $booking_id)->first();
        }

        // get booking moving
        if ($booking_type == 'moving') {
            $bookingData = BookingMoving::where('booking_id', $booking_id)->first();
        }

        // get booking secureship
        if ($booking_type == 'secureship') {
            $bookingData = BookingSecureship::where('booking_id', $booking_id)->first();
        }

        return $bookingData;
    }

    // Check if all helper requirements are met
    public function checkHelperRequirements()
    {
        // Check if user has helper_enabled
        $user = User::where('id', Auth::user()->id)->first();
        if (!$user->helper_enabled) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please enable your profile');
        }

        // Check if helper completed its profile
        $helper = Helper::where('user_id', Auth::user()->id)->first();

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
        $helperVehicle = HelperVehicle::where('user_id', Auth::user()->id)->first();
        if (!$helperVehicle) {
            return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
        }

        // Check if helper is_approved is 0
        if ($helper->is_approved != 1) {
            return redirect()->back()->with('error', 'In order to accept booking, waiting for admin approval');
        }

        // Check if vehicle detail approved
        if ($helperVehicle->is_approved != 1) {
            return redirect()->back()->with('error', 'In order to accept booking, waiting for admin approval');
        }

        // Check if profile is company profile
        if ($helper->company_enabled == 1) {
            // Check if company detail completed
            $companyData = HelperCompany::where('user_id', Auth::user()->id)->first();

            if (!$companyData) {
                return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
            }

            // Check if company detail completed

            if ($companyData->company_alias == null || $companyData->city == null) {
                return redirect()->route('helper.profile')->with('error', 'In order to accept booking please complete your profile');
            }
        }
    }
}
