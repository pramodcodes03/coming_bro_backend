<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use Illuminate\Http\JsonResponse;

class DriverController extends Controller
{
    public function show(string $id): JsonResponse
    {
        $driver = DriverUser::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Driver retrieved.',
            'data' => $driver,
        ]);
    }

    public function location(string $id): JsonResponse
    {
        $driver = DriverUser::select(['id', 'latitude', 'longitude', 'is_online'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Driver location retrieved.',
            'data' => $driver,
        ]);
    }
}
