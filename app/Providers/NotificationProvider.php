<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Helpers\NotificationHelper;

class NotificationProvider extends ServiceProvider
{
    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        // Bind the NotificationHelper class to the container as a singleton
        $this->app->singleton('notificationHelper', function () {
            return new NotificationHelper();
        });
    }
}
