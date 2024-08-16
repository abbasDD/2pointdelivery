<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\DeliveryConfig;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\SystemSetting;
use App\Models\TaxSetting;
use App\Models\User;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\File;
use Barryvdh\DomPDF\Facade\Pdf as FacadePdf;
use Carbon\Carbon;

class GetEstimateController extends Controller
{
    public function index(Request $request)
    {

        // Testing
        // return response()->json($request->all());

        // data to return
        $data = [];

        // Check if service type available for booking
        $serviceType = ServiceType::where('id', $request->selectedServiceTypeID)->where('is_active', 1)->first();
        if (!$serviceType) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service type not found',
            ]);
        }

        // Check selected selectedServiceCategoryUuid is empty
        $serviceCategory = ServiceCategory::where('uuid', $request->selectedServiceCategoryUuid)->where('is_active', 1)->first();
        if (!$serviceCategory) {
            return response()->json([
                'status' => 'error',
                'message' => 'Service category not found',
            ]);
        }

        // Check if priority setting exist
        $prioritySetting = PrioritySetting::where('id', $request->priorityID)->where('is_active', 1)->first();
        if (!$prioritySetting) {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Priority setting not found',
            // ]);

            // Get first
            $prioritySetting = PrioritySetting::where('is_active', 1)->first();
        }

        // Check if service category has secureship api enabled
        if ($serviceCategory->is_secureship_enabled) {

            // Call Secureship API function
            $data = $this->getSecureshipEstimate($request);
            // $data = $this->getSecureshipEstimate();

            // Return data
            return response()->json([
                'status' => 'success',
                'deliveryMethod' => 'secureship',
                'data' => $data,
            ]);
        }

        // Calculate distance between pickup and delivery
        $distance_in_km = 5;
        // $distance_in_km = $this->getDistance($request->pickup_latitude, $request->pickup_longitude, $request->dropoff_latitude, $request->dropoff_longitude, 'K');

        // base_weight from service category
        $data['base_weight'] = $serviceCategory->base_weight;

        // Get package value and calculate insurance
        $data['insurance_value'] = $this->getInsuranceValue($request->selectedServiceType, $request->package_value);

        // Get Base Price Value
        $data['base_price'] = $this->getBasePrice($serviceType->type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);

        // Distance Price
        $data['distance_price'] = $this->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $distance_in_km);

        // Priority Price
        $data['priority_price'] = $prioritySetting->price;

        // Vehicle Price
        $data['vehicle_price'] = $this->getVehiclePrice($serviceType->type, $serviceCategory->vehicle_type_id, $distance_in_km);

        // Weight Price
        $data['weight_price'] = $this->getWeightPrice($serviceType->type, $serviceCategory, $request->package_weight, $request->package_length, $request->package_width, $request->package_height, $request->selectedMovingDetailsID);


        // If service type is moving
        $data['no_of_room_price'] = 0;
        $data['floor_plan_price'] = 0;
        $data['floor_assess_price'] = 0;
        $data['job_details_price'] = 0;

        if ($serviceType->type == 'moving') {
            // Get Room Price
            $data['no_of_room_price'] = $this->getNoOfRoomPrice($request->selectedNoOfRoomID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Plan Price
            $data['floor_plan_price'] = $this->getFloorPlanPrice($request->selectedFloorPlanID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Floor Access Price
            $data['floor_assess_price'] = $this->getFloorAccessPrice($request->selectedFloorAssessID, $serviceCategory, $request->floor_size, $request->no_of_hours);

            // Get Job Details Price
            if ($request->selectedJobDetailsID != '') {
                $data['job_details_price'] = $this->getJobDetailsPrice($request->selectedJobDetailsID, $serviceCategory, $request->floor_size, $request->no_of_hours);
            }
        }

        // Sub Total
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'] + $data['insurance_value'];


        //  Tax Price
        $data['tax_price'] = $this->getTaxPrice($data['sub_total']);


        // Total amountToPay
        $data['amountToPay'] = $data['sub_total'] + $data['tax_price'];


        // return a json object
        // return response()->json($data);
        return response()->json([
            'status' => 'success',
            'deliveryMethod' => '2point',
            'data' => $data,
        ]);
    }


    // Get insurance value
    public function getInsuranceValue($serviceType, $package_value)
    {
        $insuranceValue = 0;

        if ($serviceType == 'moving') {
            return $insuranceValue;
        }

        if ($package_value > 0) {

            // Check if insurance enabled
            if (config('insurance') == 'enabled') {
                $insuranceValue = $this->calculateInsuranceValue($package_value);
            }
        }

        return $insuranceValue;
    }

    // getBasePrice
    public function getBasePrice($serviceType, $base_price, $moving_price_type, $floor_size, $no_of_hours)
    {
        // return $base_price;

        if ($serviceType == 'delivery') {
            return $base_price;
        }

        if ($moving_price_type == 'sqm') {
            return $base_price * $floor_size;
        }

        return $base_price * $no_of_hours;
    }

    // getDistancePrice
    public function getDistancePrice($base_distance, $extra_distance_price, $distance_in_km)
    {
        $distance_price = 0;

        if ($distance_in_km > $base_distance) {
            // If distance is greater than base distance
            $distance_price = ($distance_in_km - $base_distance) * $extra_distance_price;
        }

        return $distance_price;
    }

    // getVehiclePrice
    public function getVehiclePrice($service_type, $vehicle_type_id, $distance_in_km)
    {
        $vehicle_price  = 0;

        // Apply only if $service_type is delivery
        if ($service_type == 'delivery') {
            // Vehicle Price
            $vehicleType = VehicleType::where('id', $vehicle_type_id)->first();
            if (!$vehicleType) {
                // return response()->json([
                //     'status' => 'error',
                //     'message' => 'Vehicle type not found',
                // ]);
                return $vehicle_price;
            }
            $vehicle_price = $vehicleType->price * $distance_in_km;
        }

        return $vehicle_price;
    }

    // getWeightPrice
    public function getWeightPrice($service_type, $serviceCategory, $package_weight, $package_length, $package_width, $package_height, $selectedMovingDetailsID)
    {
        $weight_price = 0;
        if ($service_type == 'delivery') {
            $weight_price =  $this->getDeliveryWeightPrice($serviceCategory, $package_weight, $package_length, $package_width, $package_height);
        }

        if ($service_type == 'moving') {
            $weight_price =  $this->getMovingWeightPrice($serviceCategory, $selectedMovingDetailsID);
        }

        return $weight_price;
    }

    // Get weight price value of delivery
    public function getDeliveryWeightPrice($serviceCategory, $package_weight, $package_length, $package_width, $package_height)
    {
        $weight_price = 0;
        // Calculate cubic volume
        $cubicVolume = $package_length * $package_width * $package_height;

        if (config('dimension') == 'INCH') {
            $calculated_weight = $cubicVolume / 139;
        } else {
            $calculated_weight = $cubicVolume / 5000;
        }

        $package_weight = $package_weight; // package_weight

        // If caculated weight is greater than package weight then assign calcuated weight  to package weight
        if ($calculated_weight > $package_weight) {
            $package_weight = $calculated_weight;
        }

        // Now check if package weight is greater than base weight

        if ($package_weight > $serviceCategory->base_weight) {
            $weight_price = ($package_weight - $serviceCategory->base_weight) * $serviceCategory->extra_weight_price;
        } else {
            $weight_price = 0;
        }

        return $weight_price;
    }

    // Get weight price value of delivery
    public function getMovingWeightPrice($serviceCategory, $selectedMovingDetailsID)
    {

        // IF moving details for this category is false then return 0

        if ($serviceCategory->moving_details_enabled == 0) {
            return 0;
        }

        $weight_price = 0;

        $total_weight = 0;

        // Check if selectedMovingDetailsID is array
        if (is_array($selectedMovingDetailsID)) {
            $selectedMovingDetailsID = $selectedMovingDetailsID;
        } else {
            $selectedMovingDetailsID = explode(',', $selectedMovingDetailsID);
        }

        if (count($selectedMovingDetailsID) == 0) {
            return 0;
        }

        // Loop through selectedMovingDetailsID
        foreach ($selectedMovingDetailsID as $selectedMovingDetailsID) {
            // Get from movingdetails
            $movingDetails = MovingDetail::where('uuid', $selectedMovingDetailsID)->first();
            if (!$movingDetails) {
                continue;
            }

            $total_weight += $movingDetails->weight;
        }

        // If total_weight id greater than base weight
        if ($total_weight > $serviceCategory->base_weight) {
            $weight_price = ($total_weight - $serviceCategory->base_weight) * $serviceCategory->extra_weight_price;
        }

        return $weight_price;
    }

    // getNoOfRoomPrice
    public function getNoOfRoomPrice($selectedNoOfRoomID, $serviceCategory,  $floor_size, $no_of_hours)
    {
        // IF no_of_room_enabled for this category is false then return 0

        if ($serviceCategory->no_of_room_enabled == 0) {
            return 0;
        }

        // Get selected no of room id
        $noOfRoomData = MovingConfig::where('id', $selectedNoOfRoomID)->where('type', 'no_of_rooms')->first();
        if (!$noOfRoomData) {
            return 0;
        }

        if ($serviceCategory->moving_price_type == 'hour') {
            return $no_of_hours * $noOfRoomData->price;
        }

        if ($serviceCategory->moving_price_type == 'sqm') {
            return $floor_size * $noOfRoomData->price;
        }

        return 0;
    }

    // getFloorPlanPrice
    public function getFloorPlanPrice($selectedFloorPlanID, $serviceCategory,  $floor_size, $no_of_hours)
    {
        // IF floor_plan_enabled for this category is false then return 0
        if ($serviceCategory->floor_plan_enabled == 0) {
            return 0;
        }

        // Get selected no of room id
        $floorPlanData = MovingConfig::where('id', $selectedFloorPlanID)->where('type', 'floor_plan')->first();
        if (!$floorPlanData) {
            return 0;
        }

        if ($serviceCategory->moving_price_type == 'hour') {
            return $no_of_hours * $floorPlanData->price;
        }

        if ($serviceCategory->moving_price_type == 'sqm') {
            return $floor_size * $floorPlanData->price;
        }

        return 0;
    }

    // getFloorAccessPrice
    public function getFloorAccessPrice($selectedFloorAssessID, $serviceCategory,  $floor_size, $no_of_hours)
    {

        // Get selected no of room id
        $floorAssessData = MovingConfig::where('id', $selectedFloorAssessID)->where('type', 'floor_assess')->first();
        if (!$floorAssessData) {
            return 0;
        }

        // IF floor_assess_enabled for this category is false then return 0
        if ($serviceCategory->floor_assess_enabled == 0) {
            return 0;
        }

        if ($serviceCategory->moving_price_type == 'hour') {
            return $no_of_hours * $floorAssessData->price;
        }

        if ($serviceCategory->moving_price_type == 'sqm') {
            return $floor_size * $floorAssessData->price;
        }

        return 0;
    }

    // getJobDetailsPrice
    public function getJobDetailsPrice($selectedJobDetailsID, $serviceCategory,  $floor_size, $no_of_hours)
    {
        // IF job_details_enabled for this category is false then return 0

        if ($serviceCategory->job_details_enabled == 0) {
            return 0;
        }

        // Check if selectedJobDetailsID is array
        if (is_array($selectedJobDetailsID)) {
            $selectedJobDetailsIDs = $selectedJobDetailsID;
        } else {
            // Split $selectedFloorAssessID into array
            $selectedJobDetailsIDs = explode(',', $selectedJobDetailsID);
        }


        if (count($selectedJobDetailsIDs) == 0) {
            return 0;
        }

        $selectedJobDetailsPrice = 0;

        // Loop through selectedJobDetailsIDs
        foreach ($selectedJobDetailsIDs as $selectedJobDetailsID) {
            // Get selected no of room id
            $jobDetailsData = MovingConfig::where('uuid', $selectedJobDetailsID)->where('type', 'job_details')->first();
            if (!$jobDetailsData) {
                continue;
            }

            if ($serviceCategory->job_details_enabled == 0) {
                continue;
            }

            if ($serviceCategory->moving_price_type == 'hour') {
                $selectedJobDetailsPrice += $no_of_hours * $jobDetailsData->price;
            }

            if ($serviceCategory->moving_price_type == 'sqm') {
                $selectedJobDetailsPrice += $floor_size * $jobDetailsData->price;
            }
        }


        return $selectedJobDetailsPrice;
    }

    // Get tax price
    public function getTaxPrice($sub_total)
    {
        $taxPrice = 0;

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
                // $data['tax_price'] = $taxPercentage;
            }
        }


        if ($taxPercentage > 0) {
            $taxPrice = $sub_total * ($taxPercentage / 100);
        }

        return $taxPrice;
    }

    // Get client individual tax calculation
    public function getClientTax()
    {
        $taxPercentage = 0;

        // Get client address state
        $clientStateID = Client::where('user_id', Auth::user()->id)->first()->state;
        // $taxPercentage = Auth::user()->id;
        if ($clientStateID) {
            $taxSetting = TaxSetting::where('state_id', $clientStateID)->first();
            if ($taxSetting) {
                $taxPercentage = $taxSetting->gst_rate + $taxSetting->hst_rate + $taxSetting->pst_rate;
            }
        }


        return $taxPercentage;
    }

    public function getClientCompanyTax()
    {
        $taxPercentage = 0;

        // Check if user has added the tax detail
        $clientStateTaxID = ClientCompany::where('user_id', Auth::user()->id)->first();
        if (!$clientStateTaxID) {
            return 0;
        }

        $clientStateTaxID = $clientStateTaxID->gst_number;

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

    public function calculateInsuranceValue($package_value)
    {
        // Initialize insurance value to 0
        $insuranceValue = 0;

        // API endpoint
        $url = 'https://api.secursus.com/v2/parcel/get_price';

        // Request data
        $data = [
            'parcel_value' => $package_value, // Use the provided package value
            'currency' => 'usd',
        ];

        // Your API credentials
        $apiIdentifier = 'ab183263d0f51648bbaaf676eeddf8f8';
        $apiSecretKey = '76a994d52c23d2301e3fa6db0fd9ff4b';

        // Base64 encode the credentials for Basic Authentication
        $credentials = base64_encode("$apiIdentifier:$apiSecretKey");

        try {
            // Make the authenticated POST request
            $response = Http::withHeaders([
                'cache-control' => 'no-cache',
                'Authorization' => 'Basic ' . $credentials,
                'Content-Type' => 'application/json',
            ])->post($url, $data);
            // Check for a successful response
            if ($response->successful()) {
                // Process the response data as needed
                $responseData = $response->json();

                // Extract specific fields from the response
                if (isset($responseData['data'])) {

                    $insuranceValue = $responseData['data']['value'];
                } else {
                    // Handle the case where 'data' is not present
                    $insuranceValue = 'N/A';
                }
            } else {
                // Handle unsuccessful response
                $insuranceValue = 'N/A';
                // Log or handle the error response
                // Log::error('Error: ' . $response->body());
            }
        } catch (\Exception $ex) {
            // Handle the exception
            $insuranceValue = 'N/A';
            // Log::error('Exception: ' . $ex->getMessage());
        }

        return $insuranceValue;
    }

    // generateInvoice
    public function generateInvoice($booking_id)
    {
        // Check if booking exist
        $booking = Booking::where('id', $booking_id)
            ->with('prioritySetting')
            ->with('serviceType')
            ->with('serviceCategory')
            ->first();

        if (!$booking) {
            return 0;
        }

        $bookingPayment = [];

        // Check if booking->booking_type is delivery
        if ($booking->booking_type == 'delivery') {
            // Get booking delivery data
            $bookingDelivery = BookingDelivery::where('booking_id', $booking->id)->first();
            if (!$bookingDelivery) {
                return 0;
            }
            // Store to $bookingData
            $bookingPayment = $bookingDelivery;
        }

        // Check if booking->booking_type is moving
        if ($booking->booking_type == 'moving') {
            // Get booking moving data
            $bookingMoving = BookingMoving::where('booking_id', $booking->id)->first();
            if (!$bookingMoving) {
                return 0;
            }
            // Store to $bookingData
            $bookingPayment = $bookingMoving;
        }

        if (!$bookingPayment) {
            return 0;
        }

        // Get company logo
        $website_logo = SystemSetting::where('key', 'website_logo')->first();

        if ($website_logo) {
            $company_logo = asset('images/logo/' . $website_logo->value);
        } else {
            $company_logo = asset('images/logo/default.png');
        }

        // Get client details
        $client_user = User::where('id', $booking->client_user_id)->first();
        if (!$client_user) {
            return 0;
        }

        // Get Client
        $client = Client::where('user_id', $booking->client_user_id)->first();
        if (!$client) {
            return 0;
        }
        // dd($company_logo);

        $data = [
            'title' => 'Booking Invoice - ' . $booking->uuid,
            'date' => date('m/d/Y'),
            'booking' => $booking,
            'bookingPayment' => $bookingPayment,
            'company_logo' => $company_logo,
            'client_user' => $client_user,
            'client' => $client,
            'index' => 1,
        ];

        $pdf = FacadePdf::loadView('pdfs/booking-invoice', $data);

        // Define the path to save the PDF
        $path = public_path('pdfs/invoices');

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Save the PDF to the specified path
        $pdf->save($path . '/' . $booking->uuid . '.pdf');

        // return back with success message
        // return response()->json(['success' => 'Booking Invoice generated successfully']);
        return $booking->uuid . '.pdf';
    }

    // generateLabel
    public function generateLabel($booking_id)
    {
        // Check if booking exist
        $booking = Booking::where('id', $booking_id)->first();
        if (!$booking) {
            return 0;
        }

        $users = User::get();

        $data = [
            'title' => 'SHipping Label - 123',
            'date' => date('m/d/Y'),
            'users' => $users
        ];

        $pdf = FacadePdf::loadView('pdfs/shipping-label', $data);

        // Define the path to save the PDF
        $path = public_path('pdfs/shipping-labels');

        // Ensure the directory exists
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }

        // Save the PDF to the specified path
        $pdf->save($path . '/' . $booking->uuid . '.pdf');

        return $booking->uuid . '.pdf';
    }


    // getSecureshipEstimate
    // public function getSecureshipEstimate()
    public function getSecureshipEstimate(Request $request)
    {
        // Get pickup_address object from lat long
        $pickup_address = $this->getAddressFromLatLong($request->pickup_latitude, $request->pickup_longitude);

        if (!$pickup_address) {
            return false;
        }

        // Get dropoffaddress object from lat long
        $dropoff_address = $this->getAddressFromLatLong($request->dropoff_latitude, $request->dropoff_longitude);
        if (!$dropoff_address) {
            return false;
        }

        // Check if $request->secureshipPackages is present and not empty
        if ($request->secureshipPackages) {
            // Decode the JSON string into an array
            $packages = json_decode($request->secureshipPackages, true);

            // Check if the decoded array is empty
            if (empty($packages)) {
                // Handle the case where the array is empty
                $secureshipPackages = $this->getDefaultPackage($request);
            } else {
                // Process the packages to ensure correct data types
                $secureshipPackages = array_map(function ($package) use ($request) {
                    return [
                        'packageType' => $package['packageType'] ?? 'MyPackage',
                        'userDefinedPackageType' => $package['userDefinedPackageType'] ?? 'Refrigerator',
                        'weight' => isset($package['weight']) ? (float) $package['weight'] : ($request->package_weight ? (float) $request->package_weight : 1.0),
                        'weightUnits' => $package['weightUnits'] ?? 'Lbs',
                        'length' => isset($package['length']) ? (float) $package['length'] : ($request->package_length ? (float) $request->package_length : 1.0),
                        'width' => isset($package['width']) ? (float) $package['width'] : ($request->package_width ? (float) $request->package_width : 1.0),
                        'height' => isset($package['height']) ? (float) $package['height'] : ($request->package_height ? (float) $request->package_height : 1.0),
                        'dimUnits' => $package['dimUnits'] ?? 'Inches',
                        'insurance' => isset($package['insurance']) ? (float) $package['insurance'] : 0.0,
                        'isAdditionalHandling' => isset($package['isAdditionalHandling']) && $package['isAdditionalHandling'] === 'on' ? true : false,
                        'signatureOptions' => $package['signatureOptions'] ?? 'None',
                        'description' => $package['description'] ?? 'Gift',
                        'temperatureProtection' => isset($package['temperatureProtection']) ? (bool) $package['temperatureProtection'] : true,
                        'isDangerousGoods' => isset($package['isDangerousGoods']) ? (bool) $package['isDangerousGoods'] : true,
                        'isNonStackable' => isset($package['isNonStackable']) ? (bool) $package['isNonStackable'] : true
                    ];
                }, $packages);
            }
        } else {
            // Handle the case where secureshipPackages is not provided
            $secureshipPackages = $this->getDefaultPackage($request);
        }



        // dd($address);
        // return response()->json($request->package_weight);
        // Static JSON data
        $payload = [
            'fromAddress' => [
                'addr1' => $pickup_address['addr1'],
                'countryCode' => $pickup_address['countryCode'],
                'postalCode' => $pickup_address['postalCode'],
                'city' => $pickup_address['city'],
                'taxId' => '',
                'residential' => false,
                'isSaturday' => true,
                'isInside' => true,
                'isTailGate' => true,
                'isTradeShow' => true,
                'isLimitedAccess' => true,
                'appointment' => [
                    'appointmentType' => 'None',
                    'phone' => '',
                    'date' => now()->format('Y-m-d'),
                    'time' => now()->format('H:i:s')
                ]
            ],
            'toAddress' => [
                'addr1' =>  $dropoff_address['addr1'],
                'countryCode' => $dropoff_address['countryCode'],
                'postalCode' => $dropoff_address['postalCode'],
                'city' => $dropoff_address['city'],
                'taxId' => '',
                'residential' => false,
                'isSaturday' => true,
                'isInside' => true,
                'isTailGate' => true,
                'isTradeShow' => true,
                'isLimitedAccess' => true,
                'appointment' => [
                    'appointmentType' => 'None',
                    'phone' => '1234567690',
                    'date' => now()->addDays(3)->format('Y-m-d'), // Adding 3 days to the current date
                    'time' => now()->format('H:i:s')
                ]
            ],
            'packages' => $secureshipPackages,
            'shipDateTime' => Carbon::now()->toIso8601String(),
            'currencyCode' => 'CAD',
            'billingOptions' => 'Prepaid',
            'isDocumentsOnly' => true,
            'isStopinOnly' => true
        ];

        // dd($payload);
        // return response()->json($payload);

        // Get secureship API key
        $secureship_api_key = DeliveryConfig::where('key', 'secureship_api_key')->first();
        if (!$secureship_api_key) {
            return response()->json([
                'status' => 'error',
                'message' => 'Secureship API key not found',
            ]);
        }

        // API URL
        $apiUrl = 'https://secureship.ca/ship/api/v1/carriers/rates';

        // Make the API request
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
            'x-api-key' => $secureship_api_key->value,
        ])->post($apiUrl, $payload);

        if ($response->successful()) {
            // return response()->json([
            //     'status' => 'success',
            //     'data' => $response->json(),
            // ]);
            return $response->json();
        } else {
            // return response()->json([
            //     'status' => 'error',
            //     'message' => 'Failed to retrieve estimate',
            //     'error' => $response->json(),
            // ]);

            // return $response->json();

            return false;
        }
    }

    private function getDefaultPackage($request)
    {
        return [
            [
                'packageType' => 'MyPackage',
                'userDefinedPackageType' => 'Refrigerator',
                'weight' => $request->package_weight ? (float)$request->package_weight : 1.0,
                'weightUnits' => 'Lbs',
                'length' => $request->package_length ? (float)$request->package_length : 1.0,
                'width' => $request->package_width ? (float)$request->package_width : 1.0,
                'height' => $request->package_height ? (float)$request->package_height : 1.0,
                'dimUnits' => 'Inches',
                'insurance' => 0.0,
                'isAdditionalHandling' => false,
                'signatureOptions' => 'None',
                'description' => 'Gift',
                'temperatureProtection' => true,
                'isDangerousGoods' => true,
                'isNonStackable' => true
            ]
        ];
    }

    // getAddressFromLatLong
    public function getAddressFromLatLong($latitude, $longitude)
    {
        $url = "https://maps.googleapis.com/maps/api/geocode/json?latlng=" . $latitude . "," . $longitude . "&key=" . env('GOOGLE_MAPS_API_KEY');
        $response = Http::get($url);
        // $address = $response->json()['results'][0];

        if ($response->successful()) {
            $addressComponents = $response->json()['results'][0]['address_components'];
            $address = $this->parseAddressComponents($addressComponents);

            return $address;
        }


        return false;
    }

    private function parseAddressComponents($components)
    {
        $address = [
            'addr1' => '',
            'countryCode' => '',
            'postalCode' => '',
            'city' => ''
        ];

        // Check ig components object is not empty
        if (empty($components)) {
            return $address;
        }

        foreach ($components as $component) {
            if (in_array('street_number', $component['types'])) {
                $address['addr1'] = $component['long_name'];
            }
            if (in_array('route', $component['types'])) {
                $address['addr1'] .= ' ' . $component['long_name'];
            }
            if (in_array('locality', $component['types'])) {
                $address['city'] = $component['long_name'];
            }
            if (in_array('postal_code', $component['types'])) {
                $address['postalCode'] = $component['long_name'];
            }
            if (in_array('country', $component['types'])) {
                $address['countryCode'] = $component['short_name'];
            }
        }

        return $address;
    }
}
