<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\UserInfoHelper; // Make sure to import the AddressHelper class

class UserInfoProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the AddressHelper class to the container as a singleton
        $this->app->singleton('userInfoHelper', function () {
            return new UserInfoHelper();
        });
    }
}