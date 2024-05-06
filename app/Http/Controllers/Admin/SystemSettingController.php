<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\TaxSetting;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SystemSettingController extends Controller
{
    public function index()
    {
        $systemSettings = [];

        try {
            // Retrieve settings from the database if the table exists
            $settings = SystemSetting::all();

            // Set each setting as a configuration value
            foreach ($settings as $setting) {
                // config([$setting->key => $setting->value]);
                $systemSettings[$setting->key] = $setting->value ? $setting->value : null;
            }

            // dd($systemSettings);
        } catch (QueryException $e) {
            // Handle the case where the table does not exist
            // For now, we can just log the error
            // \Log::error("Error retrieving system settings: {$e->getMessage()}");

            $systemSettings = [];
        }


        // dd($systemSettings);
        return view('admin.settings.index', compact('systemSettings'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        // Validate the request
        $request->validate([
            'website_name' => 'required|string|max:255',
            'currency' => 'required|string|max:255',
            'auto_assign_driver' => 'required|string|max:255',
            'language' => 'required|string|max:255',
            'dimension' => 'required|string|max:255',
            'weight' => 'required|string|max:255',
            'declare_package_value' => 'required|string|max:255',
            'enable_insurance' => 'required|string|max:255',
        ]);

        // Store values in updated data array
        $systemSetting = $request->only('website_name', 'currency', 'auto_assign_driver', 'language', 'dimension', 'weight', 'declare_package_value', 'enable_insurance');


        // Upload the website logo
        if ($request->hasFile('website_logo')) {
            $file = $request->file('website_logo');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            // $destinationPath = asset('images/logo/');
            $destinationPath = public_path('images/logo');

            // dd($updatedFilename);
            $systemSetting['website_logo'] = $updatedFilename ?? 'default.png';


            // Move the file
            $file->move($destinationPath, $updatedFilename);
        }

        // Upload the website website_favicon
        if ($request->hasFile('website_favicon')) {
            $file = $request->file('website_favicon');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            // $destinationPath = asset('images/logo/');
            $destinationPath = public_path('images/logo');

            // dd($updatedFilename);
            $systemSetting['website_favicon'] = $updatedFilename ?? 'default.png';


            // Move the file
            $file->move($destinationPath, $updatedFilename);
        }


        // Update the system settings
        foreach ($systemSetting as $key => $value) {
            $systemSetting = SystemSetting::where('key', $key)->first();
            if ($systemSetting) {
                $systemSetting->value = $value;
                $systemSetting->save();
            } else {
                $systemSetting = new SystemSetting();
                $systemSetting->key = $key;
                $systemSetting->value = $value;
                $systemSetting->save();
            }
        }

        return redirect()->back()->with('success', 'System settings updated successfully!');
    }

    public function tax()
    {
        $taxCountries = TaxSetting::paginate(10);
        return view('admin.settings.tax', compact('taxCountries'));
    }
}
