<?php

namespace App\Http\Controllers;

use App\Models\City;
use Illuminate\Http\Request;

class CityController extends Controller
{

    // Get list of all cities and return a json

    public function cities(Request $request)
    {
        return response()->json(City::where('state_id', $request->state_id)->get());
    }
}
