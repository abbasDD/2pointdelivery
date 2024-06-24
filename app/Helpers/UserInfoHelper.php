<?php

namespace App\Helpers;

use App\Models\Client;
use App\Models\Helper;
use Illuminate\Support\Facades\Auth;

class UserInfoHelper
{

    // Check if company enabled for this client user
    public static function hasClientCompany()
    {
        $client = Client::where('user_id', Auth::user()->id)->first();

        // Check if client exist
        if (!$client) {
            return false;
        }

        // Check if client is company
        if ($client->company_enabled == 1) {
            return true;
        }


        return false;
    }


    // Check if company is enabled for user helper
    public static function hasHelperCompany()
    {
        $helper = Helper::where('user_id', Auth::user()->id)->first();

        // Check if helper exist
        if (!$helper) {
            return false;
        }

        // Check if helper is company
        if ($helper->company_enabled == 1) {
            return true;
        }


        return false;
    }
}
