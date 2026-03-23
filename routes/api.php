<?php

use App\Http\Controllers\Api\Driver\AuthController;
use App\Http\Controllers\Api\Driver\BankController;
use App\Http\Controllers\Api\Driver\ChatController;
use App\Http\Controllers\Api\Driver\CustomerController;
use App\Http\Controllers\Api\Driver\DocumentController;
use App\Http\Controllers\Api\Driver\DriverController;
use App\Http\Controllers\Api\Driver\IntercityOrderController;
use App\Http\Controllers\Api\Driver\LocationController;
use App\Http\Controllers\Api\Driver\OnboardingController;
use App\Http\Controllers\Api\Driver\OrderController;
use App\Http\Controllers\Api\Driver\ReferralController;
use App\Http\Controllers\Api\Driver\ReviewController;
use App\Http\Controllers\Api\Driver\ServiceController;
use App\Http\Controllers\Api\Driver\SettingsController;
use App\Http\Controllers\Api\Driver\SubscriptionController;
use App\Http\Controllers\Api\Driver\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('driver')->group(function () {

    // ──────────────────────────────────────────────
    // Public routes (no auth required)
    // ──────────────────────────────────────────────

    // Auth
    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);

    // Settings
    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/payment-settings', [SettingsController::class, 'paymentSettings']);
    Route::get('/currency', [SettingsController::class, 'currency']);

    // Onboarding
    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::get('/languages', [OnboardingController::class, 'languages']);

    // ──────────────────────────────────────────────
    // Protected routes (auth:sanctum required)
    // ──────────────────────────────────────────────

    Route::middleware('auth:sanctum')->group(function () {

        // Auth (protected)
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

        // Driver profile
        Route::get('/profile', [DriverController::class, 'profile']);
        Route::put('/profile', [DriverController::class, 'updateProfile']);
        Route::patch('/profile/fields', [DriverController::class, 'updateFields']);
        Route::get('/profile/{id}', [DriverController::class, 'show']);
        Route::post('/update-location', [DriverController::class, 'updateLocation']);
        Route::get('/check-exists/{uid}', [DriverController::class, 'checkExists']);
        Route::get('/count-by-year/{year}', [DriverController::class, 'countByYear']);

        // Customer
        Route::get('/customer/{id}', [CustomerController::class, 'show']);
        Route::put('/customer/{id}', [CustomerController::class, 'update']);

        // Orders
        Route::get('/orders/nearby', [OrderController::class, 'nearby']);
        Route::get('/orders/first-order/{userId}', [OrderController::class, 'firstOrder']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::put('/orders/{id}', [OrderController::class, 'update']);
        Route::post('/orders/{orderId}/accept', [OrderController::class, 'accept']);
        Route::get('/orders/{orderId}/accepted/{driverId}', [OrderController::class, 'getAcceptedDriver']);

        // Intercity Orders
        Route::get('/intercity-orders/nearby', [IntercityOrderController::class, 'nearby']);
        Route::get('/intercity-orders/first-order/{userId}', [IntercityOrderController::class, 'firstOrder']);
        Route::get('/intercity-orders/{id}', [IntercityOrderController::class, 'show']);
        Route::put('/intercity-orders/{id}', [IntercityOrderController::class, 'update']);
        Route::post('/intercity-orders/{orderId}/accept', [IntercityOrderController::class, 'accept']);
        Route::get('/intercity-orders/{orderId}/accepted/{driverId}', [IntercityOrderController::class, 'getAcceptedDriver']);

        // Documents
        Route::get('/documents', [DocumentController::class, 'index']);
        Route::get('/documents/{id}', [DocumentController::class, 'show']);
        Route::get('/driver-documents', [DocumentController::class, 'driverDocuments']);
        Route::get('/driver-documents/numbers', [DocumentController::class, 'documentNumbers']);
        Route::post('/driver-documents/upload', [DocumentController::class, 'upload']);

        // Services & Vehicle Types
        Route::get('/services', [ServiceController::class, 'services']);
        Route::get('/vehicle-types', [ServiceController::class, 'vehicleTypes']);
        Route::get('/districts', [ServiceController::class, 'districts']);
        Route::get('/insurance-companies', [ServiceController::class, 'insuranceCompanies']);
        Route::get('/driver-rules', [ServiceController::class, 'driverRules']);

        // Wallet
        Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
        Route::post('/wallet/transactions', [WalletController::class, 'createTransaction']);
        Route::put('/wallet/update', [WalletController::class, 'updateWallet']);

        // Bank & Withdrawals
        Route::get('/bank-details', [BankController::class, 'show']);
        Route::put('/bank-details', [BankController::class, 'update']);
        Route::get('/bank-details/check', [BankController::class, 'check']);
        Route::post('/withdraw', [BankController::class, 'withdraw']);
        Route::get('/withdrawals', [BankController::class, 'withdrawals']);

        // Referral
        Route::get('/referral', [ReferralController::class, 'show']);
        Route::put('/referral', [ReferralController::class, 'update']);
        Route::post('/referral', [ReferralController::class, 'store']);
        Route::get('/referral/logs', [ReferralController::class, 'logs']);
        Route::post('/referral/update-amount', [ReferralController::class, 'updateAmount']);
        Route::post('/referral/update-intercity-amount', [ReferralController::class, 'updateIntercityAmount']);

        // Reviews
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::get('/reviews/{orderId}', [ReviewController::class, 'show']);

        // Chat
        Route::post('/chat/inbox', [ChatController::class, 'inbox']);
        Route::post('/chat/message', [ChatController::class, 'sendMessage']);
        Route::get('/chat/{orderId}/messages', [ChatController::class, 'messages']);

        // Subscriptions
        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::post('/subscriptions/history', [SubscriptionController::class, 'createHistory']);

        // Location data
        Route::get('/states', [LocationController::class, 'states']);
        Route::get('/cities', [LocationController::class, 'cities']);
        Route::get('/vehicle-companies', [LocationController::class, 'vehicleCompanies']);
        Route::get('/vehicle-models', [LocationController::class, 'vehicleModels']);
        Route::get('/fuel-types', [LocationController::class, 'fuelTypes']);
        Route::get('/zones', [LocationController::class, 'zones']);

        // File upload
        Route::post('/upload-file', [DriverController::class, 'uploadFile']);
    });
});
