<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovingConfig;
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
        $movingConfig = MovingConfig::first();

        if (!$movingConfig) {
            $movingConfig = new MovingConfig();
            $movingConfig->no_of_room_price = 1;
            $movingConfig->floor_plan_price = 1;
            $movingConfig->floor_access_price = 1;
            $movingConfig->job_details_price = 1;
            $movingConfig->save();
        }

        return view('admin.movingConfig.index', compact('movingConfig'));
    }


    public function update(Request $request)
    {
        $movingConfig = MovingConfig::first();
        $movingConfig->update($request->all());
        return redirect()->back()->with('success', 'Config updated successfully!');
    }
}
