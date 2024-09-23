<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\BookingHelper;

class BookingProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the BookingHelper class to the container as a singleton
        $this->app->singleton('bookingHelper', function () {
            return new BookingHelper();
        });
    }
}
