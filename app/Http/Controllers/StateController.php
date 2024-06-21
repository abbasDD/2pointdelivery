<?php

namespace App\Http\Controllers;

use App\Models\State;
use App\Http\Requests\StoreStateRequest;
use App\Http\Requests\UpdateStateRequest;
use Illuminate\Http\Request;

class StateController extends Controller
{
    // Get list of all states and return a json

    public function states(Request $request)
    {
        return response()->json(State::where('country_id', $request->country_id)->has('cities')->get());
    }
}
