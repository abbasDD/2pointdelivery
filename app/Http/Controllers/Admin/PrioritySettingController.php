<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PrioritySetting;
use Illuminate\Http\Request;

class PrioritySettingController extends Controller
{
    public function index()
    {
        $prioritySettings = PrioritySetting::where('is_deleted', 0)->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('admin.settings.priority.partials.list', compact('prioritySettings'))->render());
        }

        return view('admin.settings.priority.index', compact('prioritySettings'));
    }

    public function create()
    {
        return view('admin.settings.priority.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|max:255',
            'description' => 'required|string',
        ]);

        $prioritySetting = new PrioritySetting();
        $prioritySetting->name = $request->name;
        $prioritySetting->price = $request->price;
        $prioritySetting->description = $request->description;
        $prioritySetting->save();

        return redirect()->route('admin.prioritySettings')->with('success', 'Priority created successfully');
    }

    public function edit(Request $request)
    {
        $prioritySetting = PrioritySetting::where('id', $request->id)->first();
        // dd($prioritySetting);;
        // Redirect to listing page if not found
        if (!$prioritySetting) {
            return redirect()->back()->with('error', 'Priority Setting not found');
        }
        return view('admin.settings.priority.edit', compact('prioritySetting'));
    }

    public function update(Request $request)
    {
        // Validate the request
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|integer|max:255',
            'description' => 'required|string',
        ]);

        // dd($request->all());
        $prioritySetting = PrioritySetting::find($request->id); // Using find() instead of where()->first()

        if ($prioritySetting) {
            // If the admin is found, update its attributes
            $prioritySetting->update($request->all());
            // Optionally, return a success response or do other actions
            return redirect()->route('admin.prioritySettings')->with('success', 'prioritySetting updated successfully!');
        } else {
            // If the admin is not found, handle the error
            // For example, return a response indicating the admin was not found
            return redirect()->back()->with('error', 'prioritySetting not found or not authorized!');
        }
    }

    public function updateStatus(Request $request)
    {
        $prioritySetting = PrioritySetting::where('id', $request->id)
            ->first();

        if ($prioritySetting) {
            $prioritySetting->update(['is_active' => !$prioritySetting->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$prioritySetting->is_active, 'message' => 'prioritySetting status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'prioritySetting not found']);
    }
}
