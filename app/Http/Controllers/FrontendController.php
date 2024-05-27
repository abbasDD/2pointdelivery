<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Models\Booking;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\Faq;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\TaxSetting;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;

class FrontendController extends Controller
{

    // Index Page or Front End Home Page
    public function index()
    {
        // Get all services 
        $serviceTypes = ServiceType::where('is_active', 1)
            ->whereHas('serviceCategories', function ($query) {
                $query->where('is_active', 1);
            })
            // ->where('type', 'delivery')      // uncomment if you want to use only delivery
            ->get();
        // dd($serviceTypes);
        return view('frontend.index', compact('serviceTypes'));
        // return view('frontend.index');
    }

    // Services Page
    public function services()
    {
        return view('frontend.services');
    }

    // About Us Page
    public function about_us()
    {
        return view('frontend.about_us');
    }

    // Help Page
    public function help()
    {
        // Get all active Faqs
        $faqs = Faq::where('is_active', 1)->get();

        return view('frontend.help', compact('faqs'));
    }

    // Join Helper Page
    public function join_helper()
    {
        // Get all active Faqs
        $faqs = Faq::where('is_active', 1)->get();

        return view('frontend.join_helper', compact('faqs'));
    }

    // Get calculation for delivery system
    public function deliveryBooking(Request $request)
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
        $data['insurance_value'] = 0;

        if ($request->package_value > 0) {

            // Check if insurance enabled
            if (config('insurance') == 'enabled') {
                $data['insurance_value'] = $this->calculateInsuranceValue($request->package_value);
            }
        }


        // Calculate 

        // Base Price
        $data['base_price'] = $serviceCategory->base_price;

        // Distance Price
        if ($request->distance_in_km > $serviceCategory->base_distance) {
            // If distance is greater than base distance
            $data['distance_price'] = ($request->distance_in_km - $serviceCategory->base_distance) * $serviceCategory->extra_distance_price;
        } else {
            // If distance is less than base distance
            $data['distance_price'] = 0;
        }

        // Priority Price
        $data['priority_price'] = $prioritySetting->price;

        // Vehicle Price
        $vehicleType = VehicleType::where('id', $serviceCategory->vehicle_type_id)->first();
        if (!$vehicleType) {
            return response()->json([
                'status' => 'error',
                'message' => 'Vehicle type not found',
            ]);
        }
        $data['vehicle_price'] = $vehicleType->price * $request->distance_in_km;

        // Calculate cubic volume
        $cubicVolume = $request->package_length * $request->package_width * $request->package_height;

        if (config('dimension') == 'INCH') {
            $calculated_weight = $cubicVolume / 139;
        } else {
            $calculated_weight = $cubicVolume / 5000;
        }

        $package_weight = $request->package_weight; // package_weight

        // If caculated weight is greater than package weight then assign calcuated weight  to package weight
        if ($calculated_weight > $package_weight) {
            $package_weight = $calculated_weight;
        }

        // Now check if package weight is greater than base weight

        if ($package_weight > $serviceCategory->base_weight) {
            $data['weight_price'] = ($package_weight - $serviceCategory->base_weight) * $serviceCategory->extra_weight_price;
        } else {
            $data['weight_price'] = 0;
        }

        // Sub Total
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'];


        //  Tax Price
        $data['tax_price'] = 0;
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
                $data['tax_price'] = $taxPercentage;
            }
        }


        if ($taxPercentage > 0) {
            $data['tax_price'] = $data['sub_total'] * ($taxPercentage / 100);
        }


        // Total amountToPay
        $data['amountToPay'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['tax_price'];


        // return a json object
        // return response()->json($data);
        return response()->json([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    // Get calculation for moving syste
    public function movingBooking(Request $request)
    {
        return response()->json($request->all());
    }

    // New Booking Route
    public function newBooking(Request $request)
    {
        // Get all services Types
        $serviceTypes = ServiceType::where('is_active', 1)
            ->whereHas('serviceCategories', function ($query) {
                $query->where('is_active', 1);
            })
            // ->where('type', 'delivery')      // uncomment if you want to use only delivery
            ->get();

        // Check if service type exist
        if (!$serviceType = $serviceTypes->firstWhere('id', $request->serviceType)) {
            // return redirect()->back()->with('error', 'Service Type not found');
            // $serviceCategories = ServiceCategory::with('serviceType')->where('service_type_id', $serviceTypes[0]->id)->with('vehicleType')->where('is_active', 1)->get();
            $serviceCategories = ServiceCategory::select('service_categories.*', 'service_types.uuid as service_uuid', 'service_types.type as service_type', 'service_types.name as service_name', 'vehicle_types.uuid as vehicle_uuid', 'vehicle_types.name as vehicle_name', 'vehicle_types.price as vehicle_price', 'vehicle_types.price_type as vehicle_price_type')
                ->join('service_types', 'service_categories.service_type_id', '=', 'service_types.id')
                ->join('vehicle_types', 'service_categories.vehicle_type_id', '=', 'vehicle_types.id')
                ->where('service_type_id', $serviceTypes[0]->id)
                ->where('service_categories.is_active', 1)
                ->get();
        } else {
            // service Categories of selected service type
            // $serviceCategories = ServiceCategory::with('serviceType')->where('service_type_id', $request->serviceType)->with('vehicleType')->where('is_active', 1)->get();
            $serviceCategories = ServiceCategory::select('service_categories.*', 'service_types.uuid as service_uuid', 'service_types.type as service_type', 'service_types.name as service_name', 'vehicle_types.uuid as vehicle_uuid', 'vehicle_types.name as vehicle_name', 'vehicle_types.price as vehicle_price', 'vehicle_types.price_type as vehicle_price_type')
                ->join('service_types', 'service_categories.service_type_id', '=', 'service_types.id')
                ->join('vehicle_types', 'service_categories.vehicle_type_id', '=', 'vehicle_types.id')
                ->where('service_type_id', $request->serviceType)
                ->where('service_categories.is_active', 1)
                ->get();
        }

        // dd($serviceCategories[0]);

        // Get priority settings
        $prioritySettings = PrioritySetting::where('is_active', 1)->get();

        if ($prioritySettings->count() == 0) {
            // Create a dummy object with option id, name, description, and price
            $prioritySetting = new PrioritySetting();
            $prioritySetting->id = 1;
            $prioritySetting->name = 'Standard';
            $prioritySetting->description = 'Standard description';
            $prioritySetting->price = 10;
            $prioritySetting->is_active = 1;
        }

        $draftBooking = null;

        if (Auth::check()) {
            // Check if draft biooking exist
            $draftBooking = Booking::where('client_user_id', auth()->user()->id)->where('status', 'draft')->first();
        }

        // Get addresses of user
        $addresses = AddressBook::where('user_id', auth()->user()->id)->get();

        // return view 
        return view('frontend.bookings.new', compact('serviceTypes', 'serviceCategories', 'prioritySettings', 'draftBooking', 'addresses'));
    }

    public function fetch_services_categories(Request $request)
    {
        // service Categories of selected service type
        // $serviceCategories = ServiceCategory::where('service_type_id', $request->serviceType)->with('serviceType')->with('vehicleType')->get();
        $serviceCategories = ServiceCategory::select('service_categories.*', 'service_types.uuid as service_uuid', 'service_types.type as service_type', 'service_types.name as service_name', 'vehicle_types.uuid as vehicle_uuid', 'vehicle_types.name as vehicle_name', 'vehicle_types.price as vehicle_price', 'vehicle_types.price_type as vehicle_price_type')
            ->join('service_types', 'service_categories.service_type_id', '=', 'service_types.id')
            ->join('vehicle_types', 'service_categories.vehicle_type_id', '=', 'vehicle_types.id')
            ->where('service_type_id', $request->serviceType)
            ->where('service_categories.is_active', 1)
            ->get();
        // return a json object
        return response()->json($serviceCategories);
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
