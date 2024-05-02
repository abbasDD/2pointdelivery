<?php

namespace App\Http\Controllers;

use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;

class FrontendController extends Controller
{

    // Index Page or Front End Home Page
    public function index()
    {
        // Get all services 
        $serviceTypes = ServiceType::where('is_active', 1)->get();

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
        return view('frontend.help');
    }

    // Join Helper Page
    public function join_helper()
    {
        return view('frontend.join_helper');
    }

    // New Booking Route
    public function new_booking(Request $request)
    {
        // Get all services Types
        $serviceTypes = ServiceType::where('is_active', 1)->get();

        // service Categories of selected service type
        $serviceCategories = ServiceCategory::with('serviceType')->where('service_type_id', $request->serviceType)->where('is_active', 1)->get();

        // return view 
        return view('frontend.new_booking', compact('serviceTypes', 'serviceCategories'));
    }

    public function fetch_services_categories(Request $request)
    {
        // service Categories of selected service type
        $serviceCategories = ServiceCategory::where('service_type_id', $request->serviceType)->with('serviceType')->get();
        // return a json object
        return response()->json($serviceCategories);
    }
}
