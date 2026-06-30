<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
        then: function () {
            Route::middleware('web')
                ->prefix('admin')
                ->group(base_path('routes/admin.php'));
        },
    )
    ->withBroadcasting(base_path('routes/channels.php'))
    ->withMiddleware(function (Middleware $middleware): void {
        // Route alias gating the driver-side Return Ride endpoints behind an
        // active Return Ride recharge.
        $middleware->alias([
            'driver.return_ride' => \App\Http\Middleware\EnsureReturnRideRecharge::class,
        ]);

        // The app sits behind a local nginx reverse proxy that forwards the
        // real client IP in X-Forwarded-For. Trust only loopback/private
        // proxies so $request->ip() returns the genuine client IP (used for
        // the customer/driver IP audit) without allowing external spoofing.
        $middleware->trustProxies(at: [
            '127.0.0.1',
            '::1',
            '10.0.0.0/8',
            '172.16.0.0/12',
            '192.168.0.0/16',
        ]);

        $middleware->redirectGuestsTo(function ($request) {
            // API guests must never be redirected — there is no `login` web
            // route, so redirecting them throws RouteNotFoundException (500).
            // Returning null lets the auth middleware raise an
            // AuthenticationException, rendered below as a clean JSON 401.
            if ($request->expectsJson() || $request->is('api/*')) {
                return null;
            }
            if ($request->is('admin/*') || $request->is('admin')) {
                return route('admin.login');
            }
            return route('admin.login');
        });
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->render(function (ValidationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => $e->getMessage(),
                    'errors' => $e->errors(),
                ], $e->status);
            }
        });

        $exceptions->render(function (AuthenticationException $e, Request $request) {
            if ($request->expectsJson() || $request->is('api/*')) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthenticated.',
                    'data' => null,
                ], 401);
            }
        });
    })->create();
