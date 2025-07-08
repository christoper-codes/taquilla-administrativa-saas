<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Support\Facades\Route;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            $methods = ['get', 'post', 'put', 'delete'];
            foreach ($methods as $method) {
                Route::middleware('web')
                    ->group(base_path("routes/web/{$method}.php"));
            }

            foreach ($methods as $method) {
                Route::prefix('api')
                    ->middleware('api')
                    ->group(base_path("routes/api/{$method}.php"));
            }
        }
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->web(append: [
            \App\Http\Middleware\HandleInertiaRequests::class,
            \Illuminate\Http\Middleware\AddLinkHeadersForPreloadedAssets::class,
            \Illuminate\Http\Middleware\HandleCors::class,
        ]);

        $middleware->api(prepend: [
            \App\Http\Middleware\XAuthorizationHeader::class,
        ]);

        $middleware->validateCsrfTokens(except: [
            'cyber-source/autenticacion-cliente',
        ]);

        //
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
