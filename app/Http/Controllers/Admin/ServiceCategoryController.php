<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{

    public function index(Request $request)
    {
        $service_categories = ServiceCategory::with('serviceType')->paginate(10); // 10 items per page
        // dd($service_categories);
        if (request()->ajax()) {
            return response()->json(view('service_categories.partials.list', compact('service_categories'))->render());
        }
        return view('admin.service_categories.index', compact('service_categories'));
    }

    public function create()
    {
        $serviceTypes = ServiceType::where('is_active', 1)->get();
        return view('admin.service_categories.create', compact('serviceTypes'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'service_type_id' => 'required|integer|exists:service_types,id',  // ensures the id exists in the service_types table
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
            'image' => 'nullable|string|max:255',        // allows null or string values
            'base_price' => 'nullable|string|max:255',        // allows null or string values
            'price_per_km' => 'nullable|string|max:255',        // allows null or string values
            'min_km_price' => 'nullable|string|max:255',        // allows null or string values
            'is_active' => 'sometimes|boolean'
        ]);

        $request->request->add([
            'uuid' => Str::random(32),
        ]);

        // Create the serviceCategory
        $serviceCategory = new ServiceCategory($request->all());

        $serviceCategory->save();

        // Redirect with a success message
        return redirect()->route('admin.serviceCategories')->with('success', 'Service Category created successfully!');
    }

    public function edit(Request $request)
    {
        $serviceTypes = ServiceType::where('is_active', 1)->get();

        $serviceCategory = ServiceCategory::with('serviceType')->where('service_categories.id', $request->id)
            ->first();

        // dd($serviceCategory);
        return view('admin.service_categories.edit', compact('serviceCategory', 'serviceTypes'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'service_type_id' => 'required|integer|exists:service_types,id',  // ensures the id exists in the service_types table
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
            'image' => 'nullable|string|max:255',        // allows null or string values
            'base_price' => 'nullable|string|max:255',        // allows null or string values
            'price_per_km' => 'nullable|string|max:255',         // allows null or string values
            'min_km_price' => 'nullable|string|max:255',       // allows null or string values
            'is_active' => 'sometimes|boolean'
        ]);

        unset($request['_token']);
        // dd($request->all());
        // Update the serviceCategory
        $serviceCategory = ServiceCategory::where('id', $request->id)->update($request->all());

        return redirect()->route('admin.serviceCategories')->with('success', 'Service Category updated successfully!');
    }

    public function updateStatus(Request $request)
    {
        $serviceCategory = ServiceCategory::where('id', $request->id)->first();
        if ($serviceCategory) {
            $serviceCategory->update(['is_active' => !$serviceCategory->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$serviceCategory->is_active, 'message' => 'Service Category status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Category not found']);
    }
}
