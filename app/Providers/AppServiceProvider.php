<?php

namespace App\Providers;

use App\Models\SystemSetting;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;
use Laravel\Passport\Passport;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Paginator::useBootstrap();

        // dd(Session::all());
        if (Session::get('applocale')) {
            // app()->setLocale($request->lang);
            App::setLocale(Session::get('applocale'));
        } else {

            $this->app->setLocale('en');
        }

        // Default
         date_default_timezone_set('UTC');

        // Check if database has system settings table in it
        if (SystemSetting::count()) {
            // Get timezone from SystemSetting
            $timezone = SystemSetting::where('key', 'timezone')->first();
            if ($timezone) {
                date_default_timezone_set($timezone->value);
            }
            // Check default timezone
            // dd(date_default_timezone_get());
        } 



        Passport::tokensExpireIn(now()->addDays(15));
        Passport::refreshTokensExpireIn(now()->addDays(30));
        Passport::personalAccessTokensExpireIn(now()->addMonths(6));
        Passport::enablePasswordGrant();
    }
}
