<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovingConfig;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class NoOfRoomController extends Controller
{
    // No of Rooms Functions

    public function create()
    {

        return view('admin.movingConfig.noOfRooms.create');
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

        // type as no_of_rooms
        $request['type'] = 'no_of_rooms';

        MovingConfig::create($request->all());

        return redirect()->route('admin.movingConfig.index')->with('success', 'No of rooms updated successfully');
    }

    public function edit(Request $request)
    {
        $noOfRoom = MovingConfig::where('id', $request->id)->first();

        return view('admin.movingConfig.noOfRooms.edit', compact('noOfRoom'));
    }

    public function update(Request $request)
    {
        $noOfRoom = MovingConfig::where('id', $request->id)->first();
        $noOfRoom->update($request->all());
        return redirect()->route('admin.movingConfig.index')->with('success', 'No of rooms updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $noOfRoom = MovingConfig::where('id', $request->id)
            ->first();

        if ($noOfRoom) {
            $noOfRoom->update(['is_active' => !$noOfRoom->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$noOfRoom->is_active, 'message' => 'FAQ status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Status not found']);
    }
}
