<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SocialLoginSetting;
use Illuminate\Http\Request;
use Illuminate\Database\QueryException;

class SocialLoginSettingController extends Controller
{

    public function index()
    {
        $socialLoginSettings = SocialLoginSetting::all()->pluck('value', 'key')->toArray();

        // dd($socialLoginSettings);
        return view('admin.settings.socialLogin.index', compact('socialLoginSettings'));
    }

    public function update(Request $request)
    {
        // dd($request->all());

        // Store values in updated data array
        $socialLoginSetting = $request->only('google_enabled', 'google_client_id', 'google_secret_id', 'google_redirect_uri', 'facebook_enabled', 'facebook_client_id', 'facebook_client_secret', 'facebook_redirect_uri');

        // dd($socialLoginSetting);
        // Update the system settings
        foreach ($socialLoginSetting as $key => $value) {
            $socialLoginSetting = SocialLoginSetting::where('key', $key)->first();
            // if payment exists and value is empty, delete the payment setting
            if ($socialLoginSetting && ($value === null || $value === '')) {
                $socialLoginSetting->delete();
                continue;
            }

            // if value is empty, continue
            if (($value === null || $value === '')) {
                continue;
            }
            // if payment setting exists, update the value
            if ($socialLoginSetting) {
                $socialLoginSetting->value = $value;
                $socialLoginSetting->save();
            } else {
                $socialLoginSetting = new SocialLoginSetting();
                $socialLoginSetting->key = $key;
                $socialLoginSetting->value = $value;
                $socialLoginSetting->save();
            }
        }

        return redirect()->back()->with('success', 'Social Login settings updated successfully!');
    }
}
