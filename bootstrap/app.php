<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\HandlePostSizeExceeded;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->alias([
            'admin_auth' => \App\Http\Middleware\AdminAuth::class,
            'csrf' => \Illuminate\Foundation\Http\Middleware\VerifyCsrfToken::class,
        ]);

        // Add middleware to handle post size exceeded errors
        $middleware->web(\App\Http\Middleware\HandlePostSizeExceeded::class);

    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
