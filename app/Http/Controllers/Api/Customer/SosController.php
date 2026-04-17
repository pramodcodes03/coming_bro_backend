<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Sos;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SosController extends Controller
{
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'driver_id' => 'nullable|integer',
            'order_id' => 'nullable|integer',
            'latitude' => 'nullable|numeric',
            'longitude' => 'nullable|numeric',
        ]);

        $sos = Sos::create([
            'user_id' => $request->user()->id,
            'driver_id' => $request->driver_id,
            'order_id' => $request->order_id,
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'SOS created.',
            'data' => $sos,
        ], 201);
    }

    public function index(Request $request): JsonResponse
    {
        $sos = Sos::where('user_id', $request->user()->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'SOS list retrieved.',
            'data' => $sos,
        ]);
    }
}
