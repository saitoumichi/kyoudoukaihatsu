<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->validateCsrfTokens(except: [
            'login',
            'logout',
            'register',
            'forgot-password',
            'reset-password/*',
        ]);
        $middleware->trustHosts([
            '15-168-43-164\.sslip\.io',
            '15\.168\.43\.164',
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
