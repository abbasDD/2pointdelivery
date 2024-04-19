<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use Illuminate\Http\Request;

class TaxSettingController extends Controller
{

    public function index(Request $request)
    {
        $taxCountries = TaxSetting::paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('settings.tax.partials.list', compact('taxCountries'))->render());
        }
        return view('admin.settings.tax.index', compact('taxCountries'));
    }


    public function create()
    {
        return view('admin.settings.tax.create');
    }
    public function store(Request $request)
    {
        // Validate the request
        $request->validate([
            'country' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'tax_type' => 'required|string|in:gst,pst,hst',
            'tax_rate' => 'required|string|max:255',
            // 'is_active' => 'required|boolean',
        ]);


        // Create the admin
        $helper = new TaxSetting([
            'country' => $request->country,
            'state' => $request->state,
            'tax_type' => $request->tax_type,
            'tax_rate' => $request->tax_rate,
            'is_active' => 1,
        ]);

        $helper->save();

        // Redirect with a success message
        return redirect()->route('admin.taxSettings')->with('success', 'Tax Country created successfully!');
    }

    public function edit(Request $request)
    {
        $taxCountry = TaxSetting::where('id', $request->id)
            ->first();
        return view('admin.settings.tax.edit', compact('taxCountry'));
    }

    public function update(Request $request)
    {

        // $taxCountry = TaxSetting::where('id', $request->id)
        //     ->first();
        // $taxCountry->update($request->all());
        return redirect()->route('admin.taxSettings')->with('success', 'Tax Country updated successfully!');
    }
}
