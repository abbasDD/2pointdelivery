<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Models\SystemSetting;
use Illuminate\Database\QueryException;

class SystemSettingsProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     *
     * @return void
     */
    public function boot()
    {
        try {
            // Retrieve settings from the database if the table exists
            $settings = SystemSetting::all();

            // Set each setting as a configuration value
            foreach ($settings as $setting) {
                config([$setting->key => $setting->value]);
            }

            // Retrieve Authentication settings from the database if the table exists
            $authSettings = SystemSetting::where('key', 'LIKE', 'auth.%')->get();

            // Set each setting as a configuration value
            foreach ($authSettings as $setting) {
                config([$setting->key => $setting->value]);
            }
        } catch (QueryException $e) {
            // Handle the case where the table does not exist
            // For now, we can just log the error
            // \Log::error("Error retrieving system settings: {$e->getMessage()}");

            $settings = [];
        }
    }

    /**
     * Register the application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
