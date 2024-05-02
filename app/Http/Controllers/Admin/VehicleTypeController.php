<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Models\VehicleType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class VehicleTypeController extends Controller
{

    public function index(Request $request)
    {
        $vehicle_types = VehicleType::with('service_types')->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('vehicle_types.partials.list', compact('vehicle_types'))->render());
        }
        return view('admin.vehicle_types.index', compact('vehicle_types'));
    }

    public function create()
    {
        // Get all services to show on form
        $services = ServiceType::where('is_active', 1)->get();
        return view('admin.vehicle_types.create', compact('services'));
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'services' => 'required|array',
        ]);

        // dd($request->input('services'));
        // die();

        // Create the admin
        $vehicle = new VehicleType([
            'uuid' => Str::random(32),
            'name' => $request->name,
            'description' => $request->description,
        ]);

        $vehicle->save();

        // Adding services to a vehicle
        $vehicleType = VehicleType::find($vehicle->id);
        // $serviceIds = [1, 2, 3];  // array of service IDs
        $vehicleType->service_types()->attach($request->services);

        // Redirect with a success message
        return redirect()->route('admin.vehicleTypes')->with('success', 'Vehicle created successfully!');
    }

    public function edit(Request $request)
    {
        // Get all services to show on form
        $services = ServiceType::where('is_active', 1)->get();

        $vehicle_type = VehicleType::where('vehicle_types.id', $request->id)->with('service_types')
            ->first();

        return view('admin.vehicle_types.edit', compact('vehicle_type', 'services'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'services' => 'required|array',
        ]);

        // Find the existing vehicle type
        $vehicle = VehicleType::findOrFail($request->id);

        // Update the vehicle type attributes
        $vehicle->name = $request->name;
        $vehicle->description = $request->description;

        // Save the changes
        $vehicle->save();

        // Sync services for the vehicle type
        $vehicle->service_types()->sync($request->services);

        // Redirect with a success message
        return redirect()->route('admin.vehicleTypes')->with('success', 'Vehicle updated successfully!');
    }



    public function updateStatus(Request $request)
    {
        $vehicleType = VehicleType::where('id', $request->id)->first();
        if ($vehicleType) {
            $vehicleType->update(['is_active' => !$vehicleType->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$vehicleType->is_active, 'message' => 'Vehicle Type status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'User not found']);
    }
}
