<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProfileController extends Controller
{
    public function profile(Request $request): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => 'Profile retrieved successfully.',
            'data' => $request->user(),
        ]);
    }

    public function update(Request $request): JsonResponse
    {
        $customer = $request->user();
        $customer->update($request->only([
            'full_name', 'email', 'profile_pic', 'fcm_token',
            'country_code', 'phone_number', 'is_active',
        ]));

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $customer->fresh(),
        ]);
    }

    public function updateFcmToken(Request $request): JsonResponse
    {
        $request->validate(['fcm_token' => 'required|string']);
        $request->user()->update(['fcm_token' => $request->fcm_token]);

        return response()->json([
            'success' => true,
            'message' => 'FCM token updated successfully.',
            'data' => null,
        ]);
    }

    public function show(string $id): JsonResponse
    {
        $customer = Customer::findOrFail($id);

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully.',
            'data' => $customer,
        ]);
    }

    public function checkExists(Request $request): JsonResponse
    {
        $request->validate([
            'phone_number' => 'required|string',
            'country_code' => 'required|string',
        ]);

        $exists = Customer::where('phone_number', $request->phone_number)
            ->where('country_code', $request->country_code)
            ->exists();

        return response()->json([
            'success' => true,
            'message' => 'Check completed.',
            'data' => ['exists' => $exists],
        ]);
    }

    public function uploadFile(Request $request): JsonResponse
    {
        $request->validate(['file' => 'required|file|max:20480']);

        $path = $request->file('file')->store('customer-uploads', 'public');
        $url = Storage::disk('public')->url($path);

        return response()->json([
            'success' => true,
            'message' => 'File uploaded successfully.',
            'data' => ['url' => $url],
        ]);
    }
}
