<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ServiceType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ServiceTypeController extends Controller
{

    public function index(Request $request)
    {
        $service_types = ServiceType::paginate(10); // 10 items per page
        // dd($service_types);
        if (request()->ajax()) {
            return response()->json(view('service_types.partials.list', compact('service_types'))->render());
        }
        return view('admin.service_types.index', compact('service_types'));
    }


    public function create()
    {
        return view('admin.service_types.create');
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            // enum of service_type with option delivery & moving
            'type' => 'required|string|in:delivery,moving',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
        ]);

        $request->request->add([
            'uuid' => Str::random(16),
        ]);

        // Upload the image if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/service_types/');
            $file->move($destinationPath, $updatedFilename);
        } else {
            // If image not provided, set to null
            $updatedFilename = null;
        }

        // New Data
        $new_data = [
            'uuid' => $request->uuid,
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $updatedFilename,
        ];

        // Create the serviceType
        $serviceType = new ServiceType($new_data);

        $serviceType->save();

        // Redirect with a success message
        return redirect()->route('admin.serviceTypes')->with('success', 'Service Type created successfully!');
    }

    public function edit(Request $request)
    {

        $serviceType = ServiceType::where('id', $request->id)
            ->first();

        // dd($ServiceType);
        return view('admin.service_types.edit', compact('serviceType'));
    }

    public function update(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            // enum of service_type with option delivery & moving
            'type' => 'required|string|in:delivery,moving',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
        ]);

        $serviceType = ServiceType::where('id', $request->id)->first();

        if (!$serviceType) {
            return redirect()->route('admin.serviceTypes')->with('error', 'Service Type not found!');
        }

        unset($request['_token']);


        // Set default  image to null
        $service_image = $serviceType->image ?? null;

        // Upload the  image if provided
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('images/service_types/');

            // Check if the directory exists and create it if it doesn't
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file to the destination path
            $file->move($destinationPath, $updatedFilename);

            // Set the  image attribute to the new file name
            $service_image = $updatedFilename;

            unset($request['image']);
        }

        $new_data = [
            'type' => $request->type,
            'name' => $request->name,
            'description' => $request->description,
            'image' => $service_image
        ];


        // dd($new_data);
        // Update the serviceType
        ServiceType::where('id', $request->id)->update($new_data);

        return redirect()->route('admin.serviceTypes')->with('success', 'Service Type updated successfully!');
    }

    public function updateStatus(Request $request)
    {
        $serviceType = ServiceType::where('id', $request->id)->first();
        if ($serviceType) {
            $serviceType->update(['is_active' => !$serviceType->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$serviceType->is_active, 'message' => 'Service Type status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Service Type not found']);
    }
}
