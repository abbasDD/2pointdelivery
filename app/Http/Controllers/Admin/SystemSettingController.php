<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TaxSetting;
use Illuminate\Http\Request;

class SystemSettingController extends Controller
{
    public function index()
    {
        return view('admin.settings.index');
    }

    public function update(Request $request)
    {
        return redirect()->back();
    }

    public function tax()
    {
        $taxCountries = TaxSetting::paginate(10);
        return view('admin.settings.tax', compact('taxCountries'));
    }
}
