<?php

use App\Http\Middleware\LanguageMiddleware;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Illuminate\Http\Request;

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
            'isAdmin' => \App\Http\Middleware\IsAdmin::class,
            'isHelper' => \App\Http\Middleware\CheckIfHelperCreated::class,
            'isClient' => \App\Http\Middleware\CheckIfClientCreated::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions) {
        // 404 Not found
        $exceptions->render(function (NotFoundHttpException  $e, Request $request) {
            return response()->view('errors.404', [], 404);
        });
        // 500 Internal Server Error
        $exceptions->render(function (NotFoundHttpException  $e, Request $request) {
            return response()->view('errors.500', [], 500);
        });
    })->create();
