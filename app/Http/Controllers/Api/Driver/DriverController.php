<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\DriverUser;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class DriverController extends Controller
{
    /**
     * Get current driver profile.
     */
    public function profile(Request $request): JsonResponse
    {
        $driver = $request->user();
        $driver->load(['bankDetail', 'driverDocument', 'driverReferral']);

        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully.',
            'data' => $driver,
        ]);
    }

    /**
     * Update driver profile (full update with merge).
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $driver = $request->user();

        $fillableFields = (new DriverUser())->getFillable();
        $updateData = $request->only($fillableFields);

        // Remove 'id' from update data to prevent changing primary key
        unset($updateData['id']);

        $driver->fill($updateData);
        $driver->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $driver->fresh(),
        ]);
    }

    /**
     * Update specific driver fields.
     */
    public function updateFields(Request $request): JsonResponse
    {
        $driver = $request->user();

        $request->validate([
            'fields' => 'required|array',
        ]);

        $fillableFields = (new DriverUser())->getFillable();
        $fields = $request->fields;

        $updateData = [];
        foreach ($fields as $key => $value) {
            if (in_array($key, $fillableFields) && $key !== 'id') {
                $updateData[$key] = $value;
            }
        }

        if (!empty($updateData)) {
            $driver->fill($updateData);
            $driver->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Fields updated successfully.',
            'data' => $driver->fresh(),
        ]);
    }

    /**
     * Get driver by ID.
     */
    public function show(string $id): JsonResponse
    {
        $driver = DriverUser::find($id);

        if (!$driver) {
            return response()->json([
                'success' => false,
                'message' => 'Driver not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Driver retrieved successfully.',
            'data' => $driver,
        ]);
    }

    /**
     * Update driver location (lat, lng, rotation).
     */
    public function updateLocation(Request $request): JsonResponse
    {
        $request->validate([
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'rotation' => 'nullable|numeric',
        ]);

        $driver = $request->user();
        $driver->update([
            'location_latitude' => $request->latitude,
            'location_longitude' => $request->longitude,
            'position_latitude' => $request->latitude,
            'position_longitude' => $request->longitude,
            'rotation' => $request->rotation ?? $driver->rotation,
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Location updated successfully.',
            'data' => null,
        ]);
    }

    /**
     * Check if driver exists by UID.
     */
    public function checkExists(string $uid): JsonResponse
    {
        $exists = DriverUser::where('id', $uid)->exists();

        return response()->json([
            'success' => true,
            'message' => $exists ? 'Driver exists.' : 'Driver not found.',
            'data' => [
                'exists' => $exists,
            ],
        ]);
    }

    /**
     * Get driver count by year.
     */
    public function countByYear(int $year): JsonResponse
    {
        $count = DriverUser::whereYear('created_at', $year)->count();

        return response()->json([
            'success' => true,
            'message' => 'Driver count retrieved successfully.',
            'data' => [
                'year' => $year,
                'count' => $count,
            ],
        ]);
    }

    /**
     * Upload file (replaces Firebase Storage).
     */
    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate([
            'file' => 'required|file|max:10240',
            'path' => 'nullable|string',
            'name' => 'nullable|string',
        ]);

        $file = $request->file('file');
        $path = $request->input('path', 'uploads');
        $name = $request->input('name', $file->getClientOriginalName());

        $storedPath = $file->storeAs($path, $name, 'public');
        $url = asset('storage/' . $storedPath);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'data' => [
                'url' => $url,
                'path' => $storedPath,
            ],
        ]);
    }
}
