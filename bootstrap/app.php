<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        $middleware->global(function (Request $request, Closure $next) {
            if (!$request->isSecure() && app()->environment('production')) {
                return redirect()->secure($request->getRequestUri());
            }

            return $next($request);
        });
    })
    ->withExceptions(function (Exceptions $exceptions) {
        //
    })->create();
