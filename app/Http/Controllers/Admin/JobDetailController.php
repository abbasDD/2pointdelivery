<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class JobDetailController extends Controller
{

    public function create()
    {

        return view('admin.movingConfig.jobDetails.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'price' => 'required',
            'helper_fee' => 'required',
        ]);

        // Get a unique UUID
        $uuid = random_int(10000000, 99999999);

        do {
            $uuid = random_int(10000000, 99999999);
            $uuidExist = MovingConfig::where('uuid', $uuid)->first();
        } while ($uuidExist);

        // numeric 6 random value as uuid
        $request['uuid'] = $uuid;

        // type as floor_assess
        $request['type'] = 'job_details';

        MovingConfig::create($request->all());

        return redirect()->route('admin.movingConfig.index')->with('success', 'Job Details updated successfully');
    }

    public function edit(Request $request)
    {
        $jobDetails = MovingConfig::where('id', $request->id)->first();

        return view('admin.movingConfig.jobDetails.edit', compact('jobDetails'));
    }

    public function update(Request $request)
    {
        $jobDetails = MovingConfig::where('id', $request->id)->first();
        $jobDetails->update($request->all());
        return redirect()->route('admin.movingConfig.index')->with('success', 'Job Details updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $jobDetails = MovingConfig::where('id', $request->id)
            ->first();

        if ($jobDetails) {
            $jobDetails->update(['is_active' => !$jobDetails->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$jobDetails->is_active, 'message' => 'FAQ status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Status not found']);
    }
}
