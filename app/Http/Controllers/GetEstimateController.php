<?php

namespace App\Http\Controllers;

use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\TaxSetting;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

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
            return response()->json([
                'status' => 'error',
                'message' => 'Priority setting not found',
            ]);
        }


        // Get package value and calculate insurance
        $data['insurance_value'] = $this->getInsuranceValue($request->selectedServiceType, $request->package_value);

        // Get Base Price Value
        $data['base_price'] = $this->getBasePrice($serviceType->type, $serviceCategory->base_price, $serviceCategory->moving_price_type, $request->floor_size, $request->no_of_hours);

        // Distance Price
        $data['distance_price'] = $this->getDistancePrice($serviceCategory->base_distance, $serviceCategory->extra_distance_price, $request->distance_in_km);

        // Priority Price
        $data['priority_price'] = $prioritySetting->price;

        // Vehicle Price
        $data['vehicle_price'] = $this->getVehiclePrice($serviceType->type, $serviceCategory->vehicle_type_id, $request->distance_in_km);

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
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'];


        //  Tax Price
        $data['tax_price'] = $this->getTaxPrice($data['sub_total']);


        // Total amountToPay
        $data['amountToPay'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['no_of_room_price'] + $data['floor_plan_price'] + $data['floor_assess_price'] + $data['job_details_price'] + $data['tax_price'];


        // return a json object
        // return response()->json($data);
        return response()->json([
            'status' => 'success',
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
}
