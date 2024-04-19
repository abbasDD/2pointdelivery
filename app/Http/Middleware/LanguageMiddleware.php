<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
use Symfony\Component\HttpFoundation\Response;

class LanguageMiddleware
{
    public function handle($request, Closure $next)
    {
        if (Session::get('applocale')) {
            // app()->setLocale($request->lang);
            App::setLocale(Session::get('applocale'));
        }
        return $next($request);
    }
}
