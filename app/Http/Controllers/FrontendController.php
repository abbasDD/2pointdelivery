<?php

namespace App\Http\Controllers;

use App\Models\Faq;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;

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
            ->get();

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
    public function new_booking(Request $request)
    {
        // Get all services Types
        $serviceTypes = ServiceType::where('is_active', 1)
            ->whereHas('serviceCategories', function ($query) {
                $query->where('is_active', 1);
            })
            ->get();

        // Check if service type exist
        if (!$serviceType = $serviceTypes->firstWhere('id', $request->serviceType)) {
            // return redirect()->back()->with('error', 'Service Type not found');
            $serviceCategories = ServiceCategory::with('serviceType')->where('service_type_id', $serviceTypes[0]->id)->where('is_active', 1)->get();
        } else {
            // service Categories of selected service type
            $serviceCategories = ServiceCategory::with('serviceType')->where('service_type_id', $request->serviceType)->where('is_active', 1)->get();
        }

        // dd($serviceCategories);
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
