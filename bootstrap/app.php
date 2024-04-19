<?php

use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
        then: function () {
            //Client Routes
            Route::middleware('web', 'app_language')
                ->group(base_path('routes/client.php'));

            // Helper Routes
            Route::middleware('web', 'app_language')
                ->group(base_path('routes/helper.php'));

            // Admin Routes
            Route::middleware('web', 'app_language')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'app_language' => LanguageMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
