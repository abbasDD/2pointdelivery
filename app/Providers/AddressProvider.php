<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\AddressHelper; // Make sure to import the AddressHelper class

class AddressProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the AddressHelper class to the container as a singleton
        $this->app->singleton('addressHelper', function () {
            return new AddressHelper();
        });
    }
}
