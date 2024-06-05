<?php

namespace App\Providers;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

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
    }
}
