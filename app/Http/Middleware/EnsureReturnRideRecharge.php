<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Gates the driver-side Return Ride endpoints (browse scheduled rides, submit
 * offers) behind ride balance in the single shared ride wallet. One recharge
 * funds both city and return rides, so any driver with rides left can bid.
 * Returns a structured 403 so the driver app can surface the recharge sheet.
 */
class EnsureReturnRideRecharge
{
    public function handle(Request $request, Closure $next): Response
    {
        $driver = $request->user();

        if (! $driver || ! $driver->hasRideBalance()) {
            return response()->json([
                'success'    => false,
                'message'    => 'You have no rides left. Recharge to browse scheduled rides.',
                'error_code' => 'RIDE_RECHARGE_REQUIRED',
                'data'       => null,
            ], 403);
        }

        return $next($request);
    }
}
