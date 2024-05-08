<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PaymentSetting;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class PaymentSettingController extends Controller
{
    public function index()
    {
        $paymentSettings = [];

        try {
            // Retrieve settings from the database if the table exists
            $settings = PaymentSetting::all();

            // Set each setting as a configuration value
            foreach ($settings as $setting) {
                // config([$setting->key => $setting->value]);
                $paymentSettings[$setting->key] = $setting->value ? $setting->value : null;
            }

            // dd($paymentSettings);
        } catch (QueryException $e) {
            // Handle the case where the table does not exist
            // For now, we can just log the error
            // \Log::error("Error retrieving system settings: {$e->getMessage()}");

            $paymentSettings = [];
        }


        // dd($paymentSettings);
        return view('admin.settings.payment.index', compact('paymentSettings'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        // Validate the request
        $request->validate([
            'paypal_client_id' => 'required|string|max:255',
            'paypal_secret_id' => 'required|string|max:255',
            'stripe_publishable_key' => 'required|string|max:255',
            'stripe_secret_key' => 'required|string|max:255',
        ]);

        // Store values in updated data array
        $paymentSetting = $request->only('paypal_client_id', 'paypal_secret_id', 'stripe_publishable_key', 'stripe_secret_key',);


        // dd($paymentSetting);
        // Update the system settings
        foreach ($paymentSetting as $key => $value) {
            $paymentSetting = PaymentSetting::where('key', $key)->first();
            if ($paymentSetting) {
                $paymentSetting->value = $value;
                $paymentSetting->save();
            } else {
                $paymentSetting = new PaymentSetting();
                $paymentSetting->key = $key;
                $paymentSetting->value = $value;
                $paymentSetting->save();
            }
        }

        return redirect()->back()->with('success', 'Payment settings updated successfully!');
    }
}
