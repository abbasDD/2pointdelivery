<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Country;
use App\Models\TaxSetting;
use Illuminate\Http\Request;

class TaxSettingController extends Controller
{

    public function index(Request $request)
    {
        $taxCountries = TaxSetting::select('tax_settings.*', 'countries.name as country_name', 'states.name as state_name'/*, 'cities.name as city_name' */)
            ->join('countries', 'countries.id', '=', 'tax_settings.country_id')
            ->join('states', 'states.id', '=', 'tax_settings.state_id')
            // ->join('cities', 'cities.id', '=', 'tax_settings.city_id')
            ->paginate(10); // 10 items per page
        if (request()->ajax()) {
            return response()->json(view('settings.tax.partials.list', compact('taxCountries'))->render());
        }
        return view('admin.settings.tax.index', compact('taxCountries'));
    }


    public function create()
    {
        $countries = Country::all();
        return view('admin.settings.tax.create', compact('countries'));
    }
    public function store(Request $request)
    {
        // dd($request->all());
        // Validate the request
        $request->validate([
            'country_id' => 'required|string|max:255',
            'state_id' => 'required|string|max:255',
            // 'city_id' => 'required|string|max:255',
            'gst_rate' => 'required|string|max:255',
            'pst_rate' => 'required|string|max:255',
            'hst_rate' => 'required|string|max:255',
            // 'is_active' => 'required|boolean',
        ]);

        // Create the taxSetting
        $taxSetting = new TaxSetting($request->all());

        $taxSetting->save();

        // Redirect with a success message
        return redirect()->route('admin.taxSettings')->with('success', 'Tax Country created successfully!');
    }

    public function edit(Request $request)
    {
        $countries = Country::all();

        $taxCountry = TaxSetting::where('id', $request->id)
            ->first();
        return view('admin.settings.tax.edit', compact('taxCountry', 'countries'));
    }

    public function update(Request $request)
    {
        $request->validate([
            'country_id' => 'required|string|max:255',
            'state_id' => 'required|string|max:255',
            // 'city_id' => 'required|string|max:255',
            'gst_rate' => 'required|string|max:255',
            'pst_rate' => 'required|string|max:255',
            'hst_rate' => 'required|string|max:255',
            // 'is_active' => 'required|boolean',
        ]);

        $taxCountry = TaxSetting::where('id', $request->id)
            ->first();

        if (!$taxCountry) {
            return redirect()->route('admin.taxSettings')->with('error', 'Tax Country not found');
        }

        $taxCountry->update($request->all());

        return redirect()->route('admin.taxSettings')->with('success', 'Tax Country updated successfully!');
    }

    public function updateStatus(Request $request)
    {
        $taxSetting = TaxSetting::where('id', $request->id)->first();

        if ($taxSetting) {
            $taxSetting->update(['is_active' => !$taxSetting->is_active]);
            return json_encode(['status' => 'success', 'is_active' => !$taxSetting->is_active, 'message' => 'taxSetting status updated successfully!']);
        }
        // return redirect()->route('admin.taxSettings')->with('success', 'Tax Country Status updated successfully!');

        return json_encode(['status' => 'error', 'message' => 'taxSetting not found']);
    }
}
