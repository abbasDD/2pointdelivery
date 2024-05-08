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
        $validatedData = $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'services' => 'required|array',
        ]);

        // Upload the image if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/vehicle_types/');
            $file->move($destinationPath, $updatedFilename);
        } else {
            // If image not provided, set to null
            $updatedFilename = null;
        }

        // Create the vehicle type
        $vehicle = VehicleType::create([
            'uuid' => Str::random(32),
            'name' => $validatedData['name'],
            'description' => $validatedData['description'],
            'image' => $updatedFilename,
        ]);

        // Adding services to the vehicle type
        $vehicle->service_types()->attach($validatedData['services']);

        // Redirect with a success message
        return redirect()->route('admin.vehicleTypes')->with('success', 'Vehicle created successfully!');
    }

    public function edit(Request $request)
    {
        // Get all services to show on form
        $services = ServiceType::where('is_active', 1)->get();

        $vehicle_type = VehicleType::where('vehicle_types.id', $request->id)->with('service_types')
            ->first();



        if (!$vehicle_type) {
            return redirect()->back()->with('error', 'Vehicle not found!');
        }

        return view('admin.vehicle_types.edit', compact('vehicle_type', 'services'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'services' => 'required|array',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);


        // Find the existing vehicle type
        $vehicle_type = VehicleType::findOrFail($request->id);

        if (!$vehicle_type) {
            return redirect()->back()->with('error', 'Vehicle not found!');
        }

        // Upload the  image if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/vehicle_types/');
            $file->move($destinationPath, $updatedFilename);

            // Set the  image attribute to the new file name
            $vehicle_type->image = $updatedFilename;
        }


        // Update the vehicle type attributes
        $vehicle_type->name = $request->name;
        $vehicle_type->description = $request->description;

        // dd($vehicle_type);

        // Save the changes
        $vehicle_type->save();

        // Sync services for the vehicle type
        $vehicle_type->service_types()->sync($request->services);

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
