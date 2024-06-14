<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendSetting;
use Illuminate\Http\Request;

class FrontendSettingController extends Controller
{

    public function index()
    {

        // Privacy Policy
        $privacyPolicy = FrontendSetting::where('slug', 'privacy-policy')->first();

        // Terms and Conditions
        $termsAndConditions = FrontendSetting::where('slug', 'terms-and-conditions')->first();

        // Cancellatioon & Refund Policy
        $cancellationPolicy = FrontendSetting::where('slug', 'cancellation-policy')->first();

        return view('admin.frontendSettings.index', compact('privacyPolicy', 'termsAndConditions', 'cancellationPolicy'));
    }

    // privacyPolicyStore
    public function privacyPolicyStore(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $frontendSetting = FrontendSetting::where('slug', 'privacy-policy')->first();
        if (!$frontendSetting) {
            $frontendSetting = new FrontendSetting();
        }
        $frontendSetting->value = $request->value;
        $frontendSetting->key = 'privacy-policy';
        $frontendSetting->slug = 'privacy-policy';
        $frontendSetting->save();
        return redirect()->back()->with('success', 'Privacy Policy updated successfully');
    }

    // termsAndConditionsStore
    public function termsAndConditionsStore(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $frontendSetting = FrontendSetting::where('slug', 'terms-and-conditions')->first();
        if (!$frontendSetting) {
            $frontendSetting = new FrontendSetting();
        }
        $frontendSetting->value = $request->value;
        $frontendSetting->key = 'terms-and-conditions';
        $frontendSetting->slug = 'terms-and-conditions';
        $frontendSetting->save();

        return redirect()->back()->with('success', 'Terms and Conditions updated successfully');
    }

    // CancellationPolicyStore
    public function cancellationPolicyStore(Request $request)
    {
        $request->validate([
            'value' => 'required',
        ]);

        $frontendSetting = FrontendSetting::where('slug', 'cancellation-policy')->first();
        if (!$frontendSetting) {
            $frontendSetting = new FrontendSetting();
        }
        $frontendSetting->value = $request->value;
        $frontendSetting->key = 'cancellation-policy';
        $frontendSetting->slug = 'cancellation-policy';
        $frontendSetting->save();

        return redirect()->back()->with('success', 'Cancellation Policy updated successfully');
    }
}
