<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DeliveryConfig;
use App\Models\PrioritySetting;
use Illuminate\Http\Request;

class DeliveryConfigController extends Controller
{
    public function index()
    {
        $insuranceApi = [
            'insurance_api_enable' => '0',
            'insurance_api_identifier' => '',
            'insurance_api_secret_key' => '',
        ];

        $insurance_api_enable = DeliveryConfig::where('key', 'insurance_api_enable')->first();

        if ($insurance_api_enable) {
            $insuranceApi['insurance_api_enable'] = $insurance_api_enable->value;
        }

        $insurance_api_identifier = DeliveryConfig::where('key', 'insurance_api_identifier')->first();
        if ($insurance_api_identifier) {
            $insuranceApi['insurance_api_identifier'] = $insurance_api_identifier->value;
        }

        $insurance_api_secret_key = DeliveryConfig::where('key', 'insurance_api_secret_key')->first();
        if ($insurance_api_secret_key) {
            $insuranceApi['insurance_api_secret_key'] = $insurance_api_secret_key->value;
        }

        $secureshipApi = [
            'secureship_api_enable' => '0',
            'secureship_api_key' => '',
        ];

        $secureship_api_enable = DeliveryConfig::where('key', 'secureship_api_enable')->first();
        if ($secureship_api_enable) {
            $secureshipApi['secureship_api_enable'] = $secureship_api_enable->value;
        }

        $secureship_api_key = DeliveryConfig::where('key', 'secureship_api_key')->first();
        if ($secureship_api_key) {
            $secureshipApi['secureship_api_key'] = $secureship_api_key->value;
        }

        // secureship_fee
        $secureship_fee = DeliveryConfig::where('key', 'secureship_fee')->first();
        if ($secureship_fee) {
            $secureshipApi['secureship_fee'] = $secureship_fee->value;
        }

        // Priority Settings
        $prioritySettings = PrioritySetting::where('type', 'delivery')->where('is_deleted', 0)->paginate(10); // 10 items per page

        // dd($insuranceApi);

        return view('admin.deliveryConfig.index', compact('insuranceApi', 'secureshipApi', 'prioritySettings'));
    }

    public function updateInsurance(Request $request)
    {
        // dd($request->insurance_api_enable);
        $request->validate([
            'insurance_api_enable' => 'required',
        ]);

        if ($request->insurance_api_enable) {

            $request->validate([
                'insurance_api_identifier' => 'required',
                'insurance_api_secret_key' => 'required',
            ]);
        }

        // Update Insurance API Enable
        $insurance_api_enable = DeliveryConfig::where('key', 'insurance_api_enable')->first();
        if (!$insurance_api_enable) {
            $deliveryConfig = new DeliveryConfig();
            $deliveryConfig->key = 'insurance_api_enable';
            $deliveryConfig->value = $request->insurance_api_enable;
            $deliveryConfig->save();
        } else {
            $insurance_api_enable->value = $request->insurance_api_enable;
            $insurance_api_enable->save();
        }

        // Update Insurance API Identifier
        $insurance_api_identifier = DeliveryConfig::where('key', 'insurance_api_identifier')->first();
        if (!$insurance_api_identifier) {
            $deliveryConfig = new DeliveryConfig();
            $deliveryConfig->key = 'insurance_api_identifier';
            $deliveryConfig->value = $request->insurance_api_identifier ?? '';
            $deliveryConfig->save();
        } else {
            $insurance_api_identifier->value = $request->insurance_api_identifier ?? '';
            $insurance_api_identifier->save();
        }

        // Update Insurance API Secret Key
        $insurance_api_secret_key = DeliveryConfig::where('key', 'insurance_api_secret_key')->first();
        if (!$insurance_api_secret_key) {
            $deliveryConfig = new DeliveryConfig();
            $deliveryConfig->key = 'insurance_api_secret_key';
            $deliveryConfig->value = $request->insurance_api_secret_key ?? '';
            $deliveryConfig->save();
        } else {
            $insurance_api_secret_key->value = $request->insurance_api_secret_key ?? '';
            $insurance_api_secret_key->save();
        }

        return redirect()->back()->with('success', 'Insurance API Updated Successfully');
    }

    public function updateSecureship(Request $request)
    {
        $request->validate([
            'secureship_api_enable' => 'required',
        ]);

        if ($request->secureship_api_enable) {

            $request->validate([
                'secureship_api_key' => 'required',
                'secureship_fee' => 'required',
            ]);
        }

        // Update Secureship API Key
        $secureship_api_key = DeliveryConfig::where('key', 'secureship_api_key')->first();
        if (!$secureship_api_key) {
            $deliveryConfig = new DeliveryConfig();
            $deliveryConfig->key = 'secureship_api_key';
            $deliveryConfig->value = $request->secureship_api_key ?? '';
            $deliveryConfig->save();
        } else {
            $secureship_api_key->value = $request->secureship_api_key ?? '';
            $secureship_api_key->save();
        }

        // Update Secureship API Enable
        $secureship_api_enable = DeliveryConfig::where('key', 'secureship_api_enable')->first();
        if (!$secureship_api_enable) {
            $deliveryConfig = new DeliveryConfig();
            $deliveryConfig->key = 'secureship_api_enable';
            $deliveryConfig->value = $request->secureship_api_enable;
            $deliveryConfig->save();
        } else {
            $secureship_api_enable->value = $request->secureship_api_enable;
            $secureship_api_enable->save();
        }

        // Update Secureship API Fee
        $secureship_fee = DeliveryConfig::where('key', 'secureship_fee')->first();
        if (!$secureship_fee) {
            $deliveryConfig = new DeliveryConfig();
            $deliveryConfig->key = 'secureship_fee';
            $deliveryConfig->value = $request->secureship_fee ?? '';
            $deliveryConfig->save();
        } else {
            $secureship_fee->value = $request->secureship_fee ?? '';
            $secureship_fee->save();
        }

        return redirect()->back()->with('success', 'Secureship API Updated Successfully');
    }
}
