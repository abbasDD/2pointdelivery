<?php

namespace App\Http\Controllers;

use App\Models\Booking;
use App\Models\Faq;
use App\Models\PrioritySetting;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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
            ->where('type', 'delivery')
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

    // New Booking Route
    public function newBooking(Request $request)
    {
        // Get all services Types
        $serviceTypes = ServiceType::where('is_active', 1)
            ->whereHas('serviceCategories', function ($query) {
                $query->where('is_active', 1);
            })
            ->where('type', 'delivery')
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

        // return view 
        return view('frontend.bookings.new', compact('serviceTypes', 'serviceCategories', 'prioritySettings', 'draftBooking'));
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
}
