<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Http\Requests\StoreCountryRequest;
use App\Http\Requests\UpdateCountryRequest;

class CountryController extends Controller
{

    // Get list of all coutnries and return a json

    public function countries()
    {
        return response()->json(Country::all());
    }
}