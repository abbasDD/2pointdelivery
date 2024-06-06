<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceCategory;
use App\Models\ServiceType;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceCategoryController extends Controller
{

    public function index(Request $request)
    {
        // Check if service type already added
        if ($this->checkIfServiceTypesNotAdded()) {
            return redirect()->route('admin.serviceTypes')->with('error', 'You must have one enabled service to add service category');
        }

        // Check if vehicle type already added
        if ($this->checkIfVehicleTypesNotAdded()) {
            return redirect()->route('admin.vehicleTypes')->with('error', 'You must have one enabled vehicle to add service category');
        }

        $service_categories = ServiceCategory::with('serviceType')->with('vehicleType')->paginate(10); // 10 items per page
        // dd($service_categories);
        if (request()->ajax()) {
            return response()->json(view('service_categories.partials.list', compact('service_categories'))->render());
        }
        return view('admin.service_categories.index', compact('service_categories'));
    }

    public function create()
    {
        // Check if service type already added
        if ($this->checkIfServiceTypesNotAdded()) {
            return redirect()->route('admin.serviceTypes')->with('error', 'You must have one enabled service to add service category');
        }

        // Check if vehicle type already added
        if ($this->checkIfVehicleTypesNotAdded()) {
            return redirect()->route('admin.vehicleTypes')->with('error', 'You must have one enabled vehicle to add service category');
        }

        $serviceTypes = ServiceType::where('is_active', 1)->get();

        $vehicleTypes = VehicleType::where('is_active', 1)->get();

        $serviceCategory = new ServiceCategory();

        return view('admin.service_categories.create', compact('serviceTypes', 'serviceCategory', 'vehicleTypes'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'service_type_id' => 'required|integer|exists:service_types,id',  // ensures the id exists in the service_types table
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',  // ensures the id exists in the vehicle_types table
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
            'image' => 'nullable|string|max:255',        // allows null or string values
            'base_price' => 'nullable|string|max:255',        // allows null or string values
            'extra_distance_price' => 'nullable|string|max:255',        // allows null or string values
            'base_distance' => 'nullable|string|max:255',        // allows null or string values
            'base_weight' => 'nullable|string|max:255',        // allows null or string values
            'extra_weight_price' => 'nullable|string|max:255',        // allows null or string values
            'helper_fee' => 'nullable|string|max:255',        // allows null or string values
            'volume_enabled' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean'
        ]);

        $request->request->add([
            'uuid' => Str::random(32),
        ]);

        // Convvert prices to 2 decimal places
        $request->request->add([
            'base_price' => number_format($request->base_price, 2),
            'extra_distance_price' => number_format($request->extra_distance_price, 2),
            'extra_weight_price' => number_format($request->extra_weight_price, 2),
            'helper_fee' => number_format($request->helper_fee, 2),
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

        $vehicleTypes = VehicleType::where('is_active', 1)->get();

        $serviceCategory = ServiceCategory::with('serviceType')->where('service_categories.id', $request->id)
            ->first();

        // dd($serviceCategory);
        return view('admin.service_categories.edit', compact('serviceCategory', 'serviceTypes', 'vehicleTypes'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'service_type_id' => 'required|integer|exists:service_types,id',  // ensures the id exists in the service_types table
            'vehicle_type_id' => 'required|integer|exists:vehicle_types,id',  // ensures the id exists in the vehicle_types table
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
            'image' => 'nullable|string|max:255',        // allows null or string values
            'base_price' => 'nullable|string|max:255',        // allows null or string values
            'extra_distance_price' => 'nullable|string|max:255',         // allows null or string values
            'base_distance' => 'nullable|string|max:255',       // allows null or string values
            'base_weight' => 'nullable|string|max:255',        // allows null or string values
            'extra_weight_price' => 'nullable|string|max:255',        // allows null or string values
            'helper_fee' => 'nullable|string|max:255',        // allows null or string values
            'volume_enabled' => 'nullable|string|max:255',
            'is_active' => 'sometimes|boolean'
        ]);

        unset($request['_token']);
        // dd($request->all());

        // Convvert prices to 2 decimal places
        $request->request->add([
            'base_price' => number_format($request->base_price, 2),
            'extra_distance_price' => number_format($request->extra_distance_price, 2),
            'extra_weight_price' => number_format($request->extra_weight_price, 2),
            'helper_fee' => number_format($request->helper_fee, 2),
        ]);

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

    private function checkIfServiceTypesNotAdded()
    {

        // Check if service type already added
        $serviceTypes = ServiceType::where('is_active', 1)->get();

        if (!count($serviceTypes)) {
            return true;
        }

        return false;
    }

    private function checkIfVehicleTypesNotAdded()
    {

        // Check if vehicle type already added
        $vehicleTypes = VehicleType::where('is_active', 1)->get();

        if (!count($vehicleTypes)) {
            return true;
        }

        return false;
    }

    // Get vehicle types as per service type
    public function getVehicleTypes(Request $request)
    {
        $vehicleTypes = VehicleType::where('service_type_id', $request->service_type_id)->where('is_active', 1)->get();

        return response()->json(['success' => 'true', 'vehicleTypes' => $vehicleTypes]);
    }
}
