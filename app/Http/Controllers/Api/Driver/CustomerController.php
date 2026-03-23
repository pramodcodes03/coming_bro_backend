<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CustomerController extends Controller
{
    /**
     * Get customer data by ID.
     */
    public function show(string $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Customer retrieved successfully.',
            'data' => $customer,
        ]);
    }

    /**
     * Update customer data.
     */
    public function update(Request $request, string $id): JsonResponse
    {
        $customer = Customer::find($id);

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer not found.',
                'data' => null,
            ], 404);
        }

        $fillableFields = (new Customer())->getFillable();
        $updateData = $request->only($fillableFields);
        unset($updateData['id']);

        $customer->fill($updateData);
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Customer updated successfully.',
            'data' => $customer->fresh(),
        ]);
    }
}
