<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use App\Models\PrioritySetting;
use App\Models\SmtpSetting;
use App\Models\SocialLoginSetting;
use App\Models\SystemSetting;
use App\Models\TaxSetting;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SystemSettingController extends Controller
{
    public function index()
    {
        // Get timezone lists
        $timezones = DateTimeZone::listIdentifiers();


        // Get System Settings
        $systemSettings = SystemSetting::all()->pluck('value', 'key')->toArray();

        // Tax Countries List
        $taxCountries = TaxSetting::select('tax_settings.*', 'countries.name as country_name', 'states.name as state_name'/*, 'cities.name as city_name' */)
            ->join('countries', 'countries.id', '=', 'tax_settings.country_id')
            ->join('states', 'states.id', '=', 'tax_settings.state_id')
            // ->join('cities', 'cities.id', '=', 'tax_settings.city_id')
            ->paginate(10); // 10 items per page

        // Payment Settings

        $paymentSettings = PaymentSetting::all()->pluck('value', 'key')->toArray();

        // Social Login Settings
        $socialLoginSettings = SocialLoginSetting::all()->pluck('value', 'key')->toArray();

        // SMTP Settings
        $smtpSettings = SmtpSetting::all()->pluck('value', 'key')->toArray();

        return view('admin.settings.index', compact('systemSettings', 'taxCountries', 'timezones', 'paymentSettings',  'socialLoginSettings', 'smtpSettings'));
    }

    public function system()
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
        return view('admin.settings.system', compact('systemSettings'));
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
            'insurance' => 'required|string|max:255',
            'timezone' => 'required|string|max:255',
            'time_format' => 'required|string|max:255',
            'date_format' => 'required|string|max:255',
        ]);

        // Store values in updated data array
        $systemSetting = $request->only(
            'website_name',
            'currency',
            'auto_assign_driver',
            'language',
            'dimension',
            'weight',
            'declare_package_value',
            'insurance',
            'timezone',
            'time_format',
            'date_format',
        );


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

        // Upload the white logo
        if ($request->hasFile('white_logo')) {
            $file = $request->file('white_logo');
            $updatedFilename = time() . '.' . $file->getClientOriginalExtension();
            // $destinationPath = asset('images/logo/');
            $destinationPath = public_path('images/logo');

            // dd($updatedFilename);
            $systemSetting['white_logo'] = $updatedFilename ?? 'default.png';


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

        // dd($systemSetting);
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

    // mapKeyUpdate
    public function mapKeyUpdate(Request $request)
    {
        $request->validate([
            'google_map_api_key' => 'required',
        ]);

        $systemSetting = SystemSetting::where('key', 'google_map_api_key')->first();
        if ($systemSetting) {
            $systemSetting->value = $request->google_map_api_key;
            $systemSetting->save();
        } else {
            $systemSetting = new SystemSetting();
            $systemSetting->key = 'google_map_api_key';
            $systemSetting->value = $request->google_map_api_key;
            $systemSetting->save();
        }

        return redirect()->back()->with('success', 'Map key updated successfully!');
    }
}
