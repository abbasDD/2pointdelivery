<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SmtpSetting;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SmtpSettingController extends Controller
{
    public function index()
    {
        $smtpSettings = [];

        try {
            // Retrieve settings from the database if the table exists
            $settings = SmtpSetting::all();

            // Set each setting as a configuration value
            foreach ($settings as $setting) {
                // config([$setting->key => $setting->value]);
                $smtpSettings[$setting->key] = $setting->value ? $setting->value : null;
            }

            // dd($smtpSettings);
        } catch (QueryException $e) {
            // Handle the case where the table does not exist
            // For now, we can just log the error
            // \Log::error("Error retrieving system settings: {$e->getMessage()}");

            $smtpSettings = [];
        }


        // dd($smtpSettings);
        return view('admin.settings.smtp.index', compact('smtpSettings'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        // Store values in updated data array
        $smtpSetting = $request->only('smtp_enabled', 'smtp_host', 'smtp_port', 'smtp_username', 'smtp_password', 'smtp_encryption', 'smtp_from_email', 'smtp_from_name');

        // dd($smtpSetting);
        // Update the system settings
        foreach ($smtpSetting as $key => $value) {
            $smtpSetting = SmtpSetting::where('key', $key)->first();
            // if payment exists and value is empty, delete the payment setting
            if ($smtpSetting && ($value === null || $value === '')) {
                $smtpSetting->delete();
                continue;
            }

            // if value is empty, continue
            if (($value === null || $value === '')) {
                continue;
            }
            // if payment setting exists, update the value
            if ($smtpSetting) {
                $smtpSetting->value = $value;
                $smtpSetting->save();
            } else {
                $smtpSetting = new SmtpSetting();
                $smtpSetting->key = $key;
                $smtpSetting->value = $value;
                $smtpSetting->save();
            }
        }

        return redirect()->back()->with('success', 'Social Login settings updated successfully!');
    }
}
