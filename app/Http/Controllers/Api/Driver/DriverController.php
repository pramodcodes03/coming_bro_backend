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
     * Validation rules for driver profile fields.
     */
    private function profileValidationRules(): array
    {
        return [
            // Integer FK fields
            'service_id' => 'nullable|integer',
            'vehicle_type_id' => 'nullable|integer',
            'district_id' => 'nullable|integer',
            'subscription_id' => 'nullable|integer',
            'complimentary_rides' => 'nullable|integer',

            // Boolean fields
            'document_verification' => 'nullable|boolean',
            'is_online' => 'nullable|boolean',
            'is_subscription_enable' => 'nullable|boolean',
            'carrier' => 'nullable|boolean',

            // Numeric fields
            'location_latitude' => 'nullable|numeric',
            'location_longitude' => 'nullable|numeric',
            'rotation' => 'nullable|numeric',
            'position_latitude' => 'nullable|numeric',
            'position_longitude' => 'nullable|numeric',

            // String fields
            'email' => 'nullable|string|email|max:100',
            'phone_number' => 'nullable|string|max:20',
            'full_name' => 'nullable|string|max:100',
            'gender' => 'nullable|string|max:20',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'pin_code' => 'nullable|string|max:10',
            'vehicle_number' => 'nullable|string|max:30',
            'vehicle_type' => 'nullable|string|max:50',

            // JSON fields
            'zone_ids' => 'nullable|array',
            'driver_rules' => 'nullable|array',

            // Date fields
            'registration_date' => 'nullable|date',
            'subscription_expired_at' => 'nullable|date',
            'subscription_date' => 'nullable|date',
            'subscription_end_date' => 'nullable|date',
            'subscription_start_date' => 'nullable|date',
        ];
    }

    /**
     * Update driver profile (full update with merge).
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $request->validate($this->profileValidationRules());

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

        // Validate the fields values against profile rules
        $profileRules = $this->profileValidationRules();
        $fieldsToValidate = [];
        foreach ($request->fields as $key => $value) {
            if (isset($profileRules[$key])) {
                $fieldsToValidate["fields.$key"] = $profileRules[$key];
            }
        }
        if (!empty($fieldsToValidate)) {
            $request->validate($fieldsToValidate);
        }

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
