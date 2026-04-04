<?php

use App\Http\Middleware\CheckRole;
use App\Http\Middleware\EnsureEmailVerified;
use App\Http\Middleware\EnsureProfileComplete;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'role' => CheckRole::class,
            'email.verified' => EnsureEmailVerified::class,
            'profile.complete' => EnsureProfileComplete::class,
        ]);

        // Use Redis throttling only when phpredis extension is available
        if (extension_loaded('redis')) {
            $middleware->throttleWithRedis();
        }
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();
