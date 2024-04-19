<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use App\Models\VehicleType;
use Illuminate\Http\Request;

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
        $services = ServiceType::all();
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
        $services = ServiceType::all();

        $vehicle_type = VehicleType::where('vehicle_types.id', $request->id)->with('service_types')
            ->first();

        return view('admin.vehicle_types.edit', compact('vehicle_type', 'services'));
    }
}
