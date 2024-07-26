<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\KycType;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class KycTypeController extends Controller
{

    public function index()
    {
        $kycTypes = KycType::all();

        return view('admin.kycTypes.index', compact('kycTypes'));
    }


    public function create()
    {
        return view('admin.kycTypes.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Get a unique UUID
        $uuid = random_int(10000000, 99999999);

        do {
            $uuid = random_int(10000000, 99999999);
            $uuidExist = KycType::where('uuid', $uuid)->first();
        } while ($uuidExist);

        // Check if name exist
        $kycType = KycType::where('name', $request->name)->first();
        if ($kycType) {
            return redirect()->back()->with('error', 'Kyc type already exist');
        }

        // add uuid to request
        $request->merge(['uuid' => $uuid]);

        $kycType = KycType::create($request->all());

        return redirect()->route('admin.kycTypes');
    }


    public function edit(Request $request)
    {
        $kycType = KycType::find($request->id);

        return view('admin.kycTypes.edit', compact('kycType'));
    }


    public function update(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        // Check if name exist
        $kycType = KycType::where('id', '!=', $request->id)->where('name', $request->name)->first();
        if ($kycType) {
            return redirect()->back()->with('error', 'Kyc type already exist');
        }

        $kycType = KycType::find($request->id);
        $kycType->update($request->all());

        return redirect()->route('admin.kycTypes');
    }

    public function updateStatus(Request $request)
    {
        $kycType = KycType::where('id', $request->id)
            ->first();

        if ($kycType) {
            $kycType->update(['is_active' => !$kycType->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$kycType->is_active, 'message' => 'KycType status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'KycType not found']);
    }
}
