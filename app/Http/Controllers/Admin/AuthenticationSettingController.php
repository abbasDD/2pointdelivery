<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuthenticationSetting;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class AuthenticationSettingController extends Controller
{
    public function index()
    {
        $AuthenticationSettings = [];

        try {
            // Retrieve settings from the database if the table exists
            $settings = AuthenticationSetting::all();

            // Set each setting as a configuration value
            foreach ($settings as $setting) {
                // config([$setting->key => $setting->value]);
                $AuthenticationSettings[$setting->key] = $setting->value ? $setting->value : null;
            }

            // dd($AuthenticationSettings);
        } catch (QueryException $e) {
            // Handle the case where the table does not exist
            // For now, we can just log the error
            // \Log::error("Error retrieving system settings: {$e->getMessage()}");

            $AuthenticationSettings = [];
        }


        // dd($AuthenticationSettings);
        return view('admin.settings.authentication.index', compact('AuthenticationSettings'));
    }
    public function update(Request $request)
    {
        // dd($request->all());

        // Validate the request
        $request->validate([
            'google_client_id' => 'required|string|max:255',
            'google_secret_id' => 'required|string|max:255',
            'callback_url' => 'required|string|max:255',
        ]);

        // Store values in updated data array
        $AuthenticationSetting = $request->only('google_client_id', 'google_secret_id', 'callback_url');


        // dd($AuthenticationSetting);
        // Update the system settings
        foreach ($AuthenticationSetting as $key => $value) {
            $AuthenticationSetting = AuthenticationSetting::where('key', $key)->first();
            if ($AuthenticationSetting) {
                $AuthenticationSetting->value = $value;
                $AuthenticationSetting->save();
            } else {
                $AuthenticationSetting = new AuthenticationSetting();
                $AuthenticationSetting->key = $key;
                $AuthenticationSetting->value = $value;
                $AuthenticationSetting->save();
            }
        }

        return redirect()->back()->with('success', 'Authentication settings updated successfully!');
    }
}
