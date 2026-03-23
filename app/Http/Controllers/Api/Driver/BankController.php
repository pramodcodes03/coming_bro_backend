<?php

namespace App\Http\Controllers\Api\Driver;

use App\Http\Controllers\Controller;
use App\Models\BankDetail;
use App\Models\WithdrawalHistory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BankController extends Controller
{
    /**
     * Get driver's bank details.
     */
    public function show(Request $request): JsonResponse
    {
        $driver = $request->user();
        $bankDetail = BankDetail::where('user_id', $driver->id)->first();

        if (!$bankDetail) {
            return response()->json([
                'success' => false,
                'message' => 'Bank details not found.',
                'data' => null,
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Bank details retrieved successfully.',
            'data' => $bankDetail,
        ]);
    }

    /**
     * Update/create bank details.
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'bank_name' => 'required|string',
            'branch_name' => 'nullable|string',
            'account_holder_name' => 'required|string',
            'account_number' => 'required|string',
            'ifsc_code' => 'required|string',
            'other_information' => 'nullable|string',
        ]);

        $driver = $request->user();

        $bankDetail = BankDetail::updateOrCreate(
            ['user_id' => $driver->id],
            [
                'bank_name' => $request->bank_name,
                'branch_name' => $request->branch_name,
                'account_holder_name' => $request->account_holder_name,
                'account_number' => $request->account_number,
                'ifsc_code' => $request->ifsc_code,
                'other_information' => $request->other_information,
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Bank details updated successfully.',
            'data' => $bankDetail,
        ]);
    }

    /**
     * Check if bank details exist.
     */
    public function check(Request $request): JsonResponse
    {
        $driver = $request->user();
        $exists = BankDetail::where('user_id', $driver->id)->exists();

        return response()->json([
            'success' => true,
            'message' => $exists ? 'Bank details exist.' : 'Bank details not found.',
            'data' => [
                'exists' => $exists,
            ],
        ]);
    }

    /**
     * Create withdrawal request.
     */
    public function withdraw(Request $request): JsonResponse
    {
        $request->validate([
            'amount' => 'required|numeric|min:1',
            'note' => 'nullable|string',
        ]);

        $driver = $request->user();

        // Check if driver has sufficient wallet balance
        if ($driver->wallet_amount < $request->amount) {
            return response()->json([
                'success' => false,
                'message' => 'Insufficient wallet balance.',
                'data' => null,
            ], 400);
        }

        // Check if bank details exist
        $bankExists = BankDetail::where('user_id', $driver->id)->exists();
        if (!$bankExists) {
            return response()->json([
                'success' => false,
                'message' => 'Please add bank details before requesting withdrawal.',
                'data' => null,
            ], 400);
        }

        $withdrawal = WithdrawalHistory::create([
            'id' => Str::uuid()->toString(),
            'driver_id' => $driver->id,
            'amount' => $request->amount,
            'note' => $request->note,
            'payment_status' => false,
            'status' => 'pending',
        ]);

        // Deduct from wallet
        $driver->wallet_amount = $driver->wallet_amount - $request->amount;
        $driver->save();

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal request created successfully.',
            'data' => $withdrawal,
        ]);
    }

    /**
     * Get withdrawal history.
     */
    public function withdrawals(Request $request): JsonResponse
    {
        $driver = $request->user();

        $withdrawals = WithdrawalHistory::where('driver_id', $driver->id)
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Withdrawal history retrieved successfully.',
            'data' => $withdrawals,
        ]);
    }
}
