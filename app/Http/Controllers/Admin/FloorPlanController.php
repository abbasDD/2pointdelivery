<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class FloorPlanController extends Controller
{
    // Floor Plan Functions

    public function create()
    {

        return view('admin.movingConfig.floorPlan.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'helper_fee' => 'required',
        ]);

        // Get a unique UUID
        $uuid = Str::random(8);

        do {
            $uuid = Str::random(8);
            $uuidExist = MovingConfig::where('uuid', $uuid)->first();
        } while ($uuidExist);

        // numeric 6 random value as uuid
        $request['uuid'] = $uuid;

        // type as floor_plan
        $request['type'] = 'floor_plan';

        MovingConfig::create($request->all());

        return redirect()->route('admin.movingConfig.index')->with('success', 'Floor Plan updated successfully');
    }

    public function edit(Request $request)
    {
        $floorPlan = MovingConfig::where('id', $request->id)->first();

        return view('admin.movingConfig.floorPlan.edit', compact('floorPlan'));
    }

    public function update(Request $request)
    {
        $floorPlan = MovingConfig::where('id', $request->id)->first();
        $floorPlan->update($request->all());
        return redirect()->route('admin.movingConfig.index')->with('success', 'Floor Plan updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $floorPlan = MovingConfig::where('id', $request->id)
            ->first();

        if ($floorPlan) {
            $floorPlan->update(['is_active' => !$floorPlan->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$floorPlan->is_active, 'message' => 'FAQ status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Status not found']);
    }
}
