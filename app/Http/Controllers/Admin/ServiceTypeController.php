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
            'image' => 'nullable|string|max:255',        // allows null or string values
            'is_active' => 'sometimes|boolean'
        ]);

        $request->request->add([
            'uuid' => Str::random(32),
        ]);

        // Create the serviceType
        $serviceType = new ServiceType($request->all());

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
        // Validate the request
        $request->validate([
            // enum of service_type with option delivery & moving
            'type' => 'required|string|in:delivery,moving',
            'name' => 'required|string|max:255',
            'description' => 'nullable|string|max:255',  // allows null or string values
            'image' => 'nullable|string|max:255',        // allows null or string values
            'is_active' => 'sometimes|boolean'
        ]);

        unset($request['_token']);
        // dd($request->all());
        // Update the serviceType
        $serviceType = ServiceType::where('id', $request->id)->update($request->all());

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