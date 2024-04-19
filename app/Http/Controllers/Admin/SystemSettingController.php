<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SystemSetting;
use App\Models\TaxSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        $systemSettings = [];

        // Get website_logo and assign null if does not exist
        $website_logo = SystemSetting::where('name', 'website_logo')->first();
        $systemSettings['website_logo'] = $website_logo ? $website_logo->value : null;

        // Get website_favicon and assign null if does not exist
        $website_favicon = SystemSetting::where('name', 'website_favicon')->first();
        $systemSettings['website_favicon'] = $website_favicon ? $website_favicon->value : null;

        // Get website_name and assign null if does not exist
        $website_name = SystemSetting::where('name', 'website_name')->first();
        $systemSettings['website_name'] = $website_name ? $website_name->value : null;

        // Get currency and assign null if does not exist
        $currency = SystemSetting::where('name', 'currency')->first();
        $systemSettings['currency'] = $currency ? $currency->value : null;

        // Get auto_assign_driver and assign null if does not exist
        $auto_assign_driver = SystemSetting::where('name', 'auto_assign_driver')->first();
        $systemSettings['auto_assign_driver'] = $auto_assign_driver ? $auto_assign_driver->value : null;

        // Get language and assign null if does not exist
        $language = SystemSetting::where('name', 'language')->first();
        $systemSettings['language'] = $language ? $language->value : null;

        // Get primary_color and assign null if does not exist
        $primary_color = SystemSetting::where('name', 'primary_color')->first();
        $systemSettings['primary_color'] = $primary_color ? $primary_color->value : null;


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
        ]);

        // Upload the website logo
        if ($request->hasFile('website_logo')) {
            $file = $request->file('website_logo');
            $filename = time() . '.' . $file->getClientOriginalExtension();
            $destinationPath = public_path('/assets/images');

            // Check if the directory exists, if not create it
            if (!file_exists($destinationPath)) {
                mkdir($destinationPath, 0777, true);
            }

            // Move the file
            $file->move($destinationPath, $filename);
        }


        $request['website_logo'] = $filename ?? null;

        // Update the system settings
        foreach ($request->all() as $name => $value) {
            $systemSetting = SystemSetting::where('name', $name)->first();
            if ($systemSetting) {
                $systemSetting->value = $value;
                $systemSetting->save();
            } else {
                $systemSetting = new SystemSetting();
                $systemSetting->name = $name;
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
