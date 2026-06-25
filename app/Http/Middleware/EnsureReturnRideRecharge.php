<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the driver-side Return Ride endpoints (browse scheduled rides, submit
 * offers) behind an active Return Ride recharge. Returns a structured 403 so
 * the driver app can detect it and surface the recharge sheet.
 */
class EnsureReturnRideRecharge
{
    public function handle(Request $request, Closure $next): Response
    {
        $driver = $request->user();

        if (! $driver || ! $driver->hasActiveReturnRideRecharge()) {
            return response()->json([
                'success'    => false,
                'message'    => 'A Return Ride recharge is required to access scheduled rides.',
                'error_code' => 'RETURN_RIDE_RECHARGE_REQUIRED',
                'data'       => null,
            ], 403);
        }

        return $next($request);
    }
}
