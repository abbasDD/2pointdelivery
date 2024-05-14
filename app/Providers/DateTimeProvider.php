<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\DateHelper; // Make sure to import the DateHelper class

class DateTimeProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the DateHelper class to the container as a singleton
        $this->app->singleton('dateHelper', function () {
            return new DateHelper();
        });
    }
}
