<?php

namespace App\Http\Controllers;

use App\Models\Country;

class CountryController extends Controller
{

    // Get list of all coutnries and return a json

    public function countries()
    {
        // return response()->json(Country::all());
        // Order by name
        return response()->json(Country::has('states')->get());
    }
}
