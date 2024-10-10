<?php

namespace App\Http\Controllers;

use App\Models\State;
use Illuminate\Http\Request;

class StateController extends Controller
{
    // Get list of all states and return a json

    public function states(Request $request)
    {
        // order by name
        return response()->json(State::where('country_id', $request->country_id)->has('cities')->get());
    }
}
