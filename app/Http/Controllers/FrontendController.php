<?php

namespace App\Http\Controllers;

use App\Models\AddressBook;
use App\Models\Admin;
use App\Models\Blog;
use App\Models\Booking;
use App\Models\BookingDelivery;
use App\Models\BookingMoving;
use App\Models\Client;
use App\Models\ClientCompany;
use App\Models\DeliveryConfig;
use App\Models\Faq;
use App\Models\FrontendSetting;
use App\Models\HelpQuestion;
use App\Models\HelpTopic;
use App\Models\MovingConfig;
use App\Models\MovingDetailCategory;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\TaxSetting;
use App\Models\User;
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

        return view('frontend.index', compact('serviceTypes'));
    }

    // getTrackingDetail
    public function getTrackingDetail(Request $request)
    {

        // dd($tracking_number);

        // $booking  = Booking::select('uuid')->where('uuid', $trackingCode)->first();
        $booking = Booking::select('id', 'uuid', 'booking_type', 'pickup_address', 'dropoff_address', 'pickup_latitude', 'pickup_longitude', 'dropoff_latitude', 'dropoff_longitude', 'booking_date', 'booking_time', 'status', 'booking_at', 'completed_at')
            ->where('bookings.uuid', $request->trackingCode)
            ->first();

        if (!$booking) {
            return response()->json(['success' => false, 'message' => 'Booking not found']);
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
            case 'incompleted':
                $booking->currentStatus = 5;
                break;
            default:
                $booking->currentStatus = 1;
                break;
        }

        return response()->json(['success' => true, 'data' => $booking]);
    }

    public function track_booking(Request $request)
    {
        // dd($request->tracking_code);
        $booking = null;
        $bookingPayment = null;

        if (isset($request->tracking_code)) {
            $booking = Booking::where('uuid', $request->tracking_code)
                ->with('prioritySetting')
                ->with('serviceType')
                ->with('serviceCategory')
                ->first();

            if (!$booking) {
                return redirect()->back()->with('error', 'Booking not found');
            }
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

            // dd($booking);
            return view('frontend.track_order', compact('booking', 'bookingPayment'));
        }
        return view('frontend.track_order', compact('booking', 'bookingPayment'));
    }

    // Services Page
    public function services()
    {
        // Get all services types
        $serviceTypes = ServiceType::where('is_active', 1)->get();

        return view('frontend.services', compact('serviceTypes'));
    }

    // About Us Page
    public function about_us()
    {
        return view('frontend.about_us');
    }

    // About Us Page
    public function contact_us()
    {
        return view('frontend.contact_us');
    }

    // Help Page
    public function help()
    {
        // Get all active Help Topics
        $helpTopics = HelpTopic::where('is_active', 1)->with('helpQuestions')->has('helpQuestions')->get();

        // Get all active Faqs
        $faqs = Faq::where('is_active', 1)->get();

        return view('frontend.help', compact('faqs', 'helpTopics'));
    }

    // // topicQuestionList
    // public function topicQuestionList(Request $request)
    // {
    //     // dd($request->all());
    //     // Topic Details
    //     $topic = HelpTopic::where('id', $request->id)->where('is_active', 1)->first();

    //     if (!$topic) {
    //         return redirect()->back()->with('error', 'Help Topic not found');
    //     }
    //     // Get all questions for selected topic
    //     $helpQuestions = HelpQuestion::where('help_topic_id', $request->id)->where('is_active', 1)->get();

    //     return view('frontend.topicQuestionList', compact('helpQuestions', 'topic'));
    // }

    // topicQuestionList
    public function topicQuestionList(Request $request)
    {
        // dd($request->all());
        // Topic Details
        $helpTopic = HelpTopic::where('id', $request->id)->where('is_active', 1)->first();

        if (!$helpTopic) {
            return redirect()->back()->with('error', 'Help Topic not found');
        }
        // Get all questions for selected topic
        $helpQuestionList = HelpQuestion::where('help_topic_id', $request->id)->where('is_active', 1)->get();

        // Get first question
        $helpQuestion = HelpQuestion::where('help_topic_id', $request->id)->where('is_active', 1)->first();
        if (!$helpQuestion) {
            return redirect()->back()->with('error', 'Help Topic not found');
        }

        return view('frontend.topicQuestion', compact('helpQuestionList', 'helpQuestion', 'helpTopic'));
    }

    // topicQuestionList
    public function topicQuestion(Request $request)
    {
        // dd($request->id);
        // Topic Details
        $helpQuestion = HelpQuestion::where('id', $request->id)->where('is_active', 1)->first();

        if (!$helpQuestion) {
            return redirect()->back()->with('error', 'Help Topic not found');
        }

        // Get Topic Details
        $helpTopic = HelpTopic::where('id', $helpQuestion->help_topic_id)->where('is_active', 1)->first();

        // Get all questions for selected topic
        $helpQuestionList = HelpQuestion::where('help_topic_id', $helpQuestion->help_topic_id)->where('is_active', 1)->get();

        // dd($helpQuestion);

        return view('frontend.topicQuestion', compact('helpQuestionList', 'helpQuestion', 'helpTopic'));
    }

    // topic search
    public function topicSearch(Request $request)
    {
        $query = $request->input('query');

        // Perform your search logic here, for example:
        $results = HelpTopic::where('name', 'LIKE', "%{$query}%")->get();

        return response()->json($results);
    }

    // Join Helper Page
    public function join_helper()
    {
        // Get all active Faqs
        $faqs = Faq::where('is_active', 1)->get();

        return view('frontend.join_helper', compact('faqs'));
    }

    // blog page
    public function blog()
    {
        // Get all active blogs
        $blogs = Blog::where('is_active', 1)->get();

        return view('frontend.blog', compact('blogs'));
    }

    // blogDetails
    public function blogDetails($id)
    {
        $blog = Blog::where('id', $id)->where('is_active', 1)->first();

        if (!$blog) {
            return redirect()->back()->with('error', 'Blog not found');
        }

        $author_name = '2 Point Delivery';

        $author = User::find($blog->author);
        if ($author) {
            // Find admin
            $admin = Admin::where('id', $author->id)->first();
            if ($admin) {
                $author_name = $admin->first_name . ' ' . $admin->last_name;
            }
        }

        $blog->author = $author_name;

        return view('frontend.blog_details', compact('blog'));
    }

    // Terms and Conditions Page
    public function terms_and_conditions()
    {
        // Get Terms and Conditions from frontend settings
        $frontendSettings = FrontendSetting::where('key', 'terms-and-conditions')->first();

        if (!$frontendSettings) {
            return redirect()->back()->with('error', 'Terms and Conditions not found');
        }
        $terms_and_conditions =  $frontendSettings->value;

        return view('frontend.terms_and_conditions', compact('terms_and_conditions'));
    }

    // privacy_policy
    public function privacy_policy()
    {
        // Get Privacy Policy from frontend settings
        $frontendSettings = FrontendSetting::where('key', 'privacy-policy')->first();

        if (!$frontendSettings) {
            return redirect()->back()->with('error', 'Privacy Policy not found');
        }
        $privacy_policy =  $frontendSettings->value;

        return view('frontend.privacy_policy', compact('privacy_policy'));
    }

    // cancellation_policy
    public function cancellation_policy()
    {
        // Get Cancellation Policy from frontend settings
        $frontendSettings = FrontendSetting::where('key', 'cancellation-policy')->first();

        if (!$frontendSettings) {
            return redirect()->back()->with('error', 'Cancellation Policy not found');
        }
        $cancellation_policy =  $frontendSettings->value;

        return view('frontend.cancellation_policy', compact('cancellation_policy'));
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
        $data['sub_total'] = $data['base_price'] + $data['distance_price'] + $data['priority_price'] + $data['vehicle_price'] + $data['weight_price'] + $data['insurance_value'];


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
        $data['amountToPay'] = $data['sub_total'] + $data['tax_price'];


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
            $prioritySetting->price = 0;
            $prioritySetting->is_active = 1;
        }

        $draftBooking = null;

        if (Auth::check()) {
            // Check if draft biooking exist
            $draftBooking = Booking::where('client_user_id', auth()->user()->id)->where('status', 'draft')->first();
        }

        // Get addresses of user
        $addresses = [];

        if (Auth::check()) {
            $addresses = AddressBook::where('user_id', auth()->user()->id)->get();
        }


        // Get moving config for no_of_rooms, floor_plans, floor_assess, job_details
        $no_of_rooms = MovingConfig::where('type', 'no_of_rooms')->where('is_active', 1)->get();
        $floor_plans = MovingConfig::where('type', 'floor_plan')->where('is_active', 1)->get();
        $floor_assess = MovingConfig::where('type', 'floor_assess')->where('is_active', 1)->get();
        $job_details = MovingConfig::where('type', 'job_details')->where('is_active', 1)->get();

        // Get all moving details categories that have at least one moving detail
        $movingDetails = MovingDetailCategory::has('movingDetails')->with('movingDetails')->get();

        // dd($movingDetails[0]->movingDetails);

        // Get insurance enabled or not
        $inusranceEnabled = DeliveryConfig::where('key', 'insurance_api_enable')->where('value', '1')->first();
        if ($inusranceEnabled) {
            $inusranceEnabled = true;
        } else {
            $inusranceEnabled = false;
        }

        // return view 
        return view('frontend.bookings.new', compact('serviceTypes', 'serviceCategories', 'prioritySettings', 'draftBooking', 'addresses', 'no_of_rooms', 'floor_plans', 'floor_assess', 'job_details', 'movingDetails', 'inusranceEnabled'));
    }

    public function fetch_services_categories(Request $request)
    {
        // service Categories of selected service type
        $serviceCategories = ServiceCategory::where('service_type_id', $request->serviceType)->get();
        // $serviceCategories = ServiceCategory::select('id', 'uuid', 'name', 'description', 'is_secureship_enabled')->where('service_type_id', $request->serviceType)->get();
        // $serviceCategories = ServiceCategory::select('service_categories.*', 'service_types.uuid as service_uuid', 'service_types.type as service_type', 'service_types.name as service_name', 'vehicle_types.uuid as vehicle_uuid', 'vehicle_types.name as vehicle_name', 'vehicle_types.price as vehicle_price', 'vehicle_types.price_type as vehicle_price_type')
        //     ->join('service_types', 'service_categories.service_type_id', '=', 'service_types.id')
        //     ->join('vehicle_types', 'service_categories.vehicle_type_id', '=', 'vehicle_types.id')
        //     ->where('service_type_id', $request->serviceType)
        //     ->where('service_categories.is_active', 1)
        //     ->get();
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

    // Change language
    public function changeLanguage(Request $request)
    {
        // If user logged in
        if (Auth::check()) {
            // Get user language
            $userLanguage = User::where('id', Auth::user()->id)->first()->language_code;

            // Update language
            User::where('id', Auth::user()->id)->update([
                'language_code' => $request->lang,
            ]);
        }

        session()->put('applocale', $request->lang);
        session()->save();
        return back();
    }
}
