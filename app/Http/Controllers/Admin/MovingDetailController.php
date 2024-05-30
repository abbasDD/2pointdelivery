<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MovingDetail;
use App\Models\MovingDetailCategory;
use Illuminate\Http\Request;

class MovingDetailController extends Controller
{
    // storeCategory
    public function storeCategory(Request $request)
    {
        $request->validate([
            'name' => 'required',
        ]);

        // Check is name already exist
        $isExist = MovingDetailCategory::where('name', $request->name)->first();
        if ($isExist) {
            return redirect()->back()->with('error', 'Category already exist');
        }

        MovingDetailCategory::create($request->all());

        return redirect()->back()->with('success', 'Category created successfully');
    }

    public function create()
    {
        // Get moving detail categories
        $movingDetailCategories = MovingDetailCategory::all();

        return view('admin.movingDetail.create', compact('movingDetailCategories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'moving_detail_category_id' => 'required',
            'name' => 'required',
            'weight' => 'required',
        ]);

        // Check if volume is empty or null then set to 0
        if (empty($request->volume)) {
            $request['volume'] = 0;
        }

        // numeric 6 random value as uuid
        $request['uuid'] = random_int(100000, 999999);

        MovingDetail::create($request->all());

        return redirect()->route('admin.movingConfig.index')->with('success', 'Moving Detail updated successfully');
    }

    public function edit(Request $request)
    {
        // Get moving detail categories
        $movingDetailCategories = MovingDetailCategory::all();

        $movingDetail = MovingDetail::where('id', $request->id)->first();

        return view('admin.movingDetail.edit', compact('movingDetail', 'movingDetailCategories'));
    }

    public function update(Request $request)
    {
        $movingDetail = MovingDetail::where('id', $request->id)->first();

        // Check if volume is empty or null then set to 0
        if (empty($request->volume)) {
            $request['volume'] = 0;
        }

        $movingDetail->update($request->all());
        return redirect()->route('admin.movingConfig.index')->with('success', 'Moving Detail updated successfully');
    }

    public function updateStatus(Request $request)
    {
        $movingDetail = MovingDetail::where('id', $request->id)
            ->first();

        if ($movingDetail) {
            $movingDetail->update(['is_active' => !$movingDetail->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$movingDetail->is_active, 'message' => 'FAQ status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'Status not found']);
    }
}
