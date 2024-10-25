<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovingConfig;
use App\Models\MovingDetail;
use App\Models\PrioritySetting;
use Illuminate\Http\Request;

class MovingConfigController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }


    public function index()
    {
        $noOfRooms = MovingConfig::where('type', 'no_of_rooms')->paginate(10);

        $floorPlans = MovingConfig::where('type', 'floor_plan')->paginate(10);

        $floorAssess = MovingConfig::where('type', 'floor_assess')->paginate(10);

        $jobDetails = MovingConfig::where('type', 'job_details')->paginate(10);

        $movingDetails = MovingDetail::paginate(10);

        // Priority Settings
        $prioritySettings = PrioritySetting::where('type', 'moving')->where('is_deleted', 0)->paginate(10); // 10 items per page


        return view('admin.movingConfig.index', compact('noOfRooms', 'floorPlans', 'floorAssess', 'jobDetails', 'movingDetails', 'prioritySettings'));
    }


    public function update(Request $request)
    {
        $movingConfig = MovingConfig::first();
        $movingConfig->update($request->all());
        return redirect()->back()->with('success', 'Config updated successfully!');
    }
}
