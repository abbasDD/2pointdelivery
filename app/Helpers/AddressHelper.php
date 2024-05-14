<?php

// app/Helpers/DateHelper.php
namespace App\Helpers;

use App\Models\City;
use App\Models\Country;
use App\Models\State;

class AddressHelper
{
    // Get Country Name from Country ID
    public static function getCountryName($country_id)
    {
        // Get country name from Country
        $country = Country::find($country_id);

        if ($country) {
            return $country->name;
        }

        // reutrn - if empty
        return '-';
    }

    // Get State Name from State ID
    public static function getStateName($state_id)
    {
        // Get state name from State
        $state = State::find($state_id);

        if ($state) {
            return $state->name;
        }

        // reutrn - if empty
        return '-';
    }

    // Get City Name from City ID
    public static function getCityName($city_id)
    {
        // Get city name from City
        $city = City::find($city_id);

        if ($city) {
            return $city->name;
        }

        // reutrn - if empty
        return '-';
    }
}
