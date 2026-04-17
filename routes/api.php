<?php

use App\Http\Controllers\Api\Driver\AuthController;
use App\Http\Controllers\Api\Driver\BankController;
use App\Http\Controllers\Api\Driver\ChatController;
use App\Http\Controllers\Api\Driver\CustomerController;
use App\Http\Controllers\Api\Driver\DashboardController;
use App\Http\Controllers\Api\Driver\DocumentController;
use App\Http\Controllers\Api\Driver\DriverController;
use App\Http\Controllers\Api\Driver\IntercityOrderController;
use App\Http\Controllers\Api\Driver\LocationController;
use App\Http\Controllers\Api\Driver\OnboardingController;
use App\Http\Controllers\Api\Driver\OrderController;
use App\Http\Controllers\Api\Driver\RechargePlanController;
use App\Http\Controllers\Api\Driver\ReferralController;
use App\Http\Controllers\Api\Driver\ReviewController;
use App\Http\Controllers\Api\Driver\ServiceController;
use App\Http\Controllers\Api\Driver\SettingsController;
use App\Http\Controllers\Api\Driver\SubscriptionController;
use App\Http\Controllers\Api\Driver\WalletController;
use Illuminate\Support\Facades\Route;

// ═══════════════════════════════════════════════
// DRIVER ROUTES
// ═══════════════════════════════════════════════

Route::prefix('driver')->group(function () {

    Route::post('/send-otp', [AuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [AuthController::class, 'verifyOtp']);
    Route::post('/social-login', [AuthController::class, 'socialLogin']);

    Route::get('/settings', [SettingsController::class, 'index']);
    Route::get('/payment-settings', [SettingsController::class, 'paymentSettings']);
    Route::get('/currency', [SettingsController::class, 'currency']);

    Route::get('/onboarding', [OnboardingController::class, 'index']);
    Route::get('/languages', [OnboardingController::class, 'languages']);

    Route::get('/states', [LocationController::class, 'states']);
    Route::get('/cities', [LocationController::class, 'cities']);
    Route::get('/vehicle-companies', [LocationController::class, 'vehicleCompanies']);
    Route::get('/vehicle-models', [LocationController::class, 'vehicleModels']);
    Route::get('/fuel-types', [LocationController::class, 'fuelTypes']);
    Route::get('/zones', [LocationController::class, 'zones']);

    Route::get('/recharge-plans', [RechargePlanController::class, 'index']);
    Route::get('/recharge-plans/{id}', [RechargePlanController::class, 'show']);

    Route::middleware('auth:sanctum')->group(function () {

        Route::post('/logout', [AuthController::class, 'logout']);
        Route::delete('/delete-account', [AuthController::class, 'deleteAccount']);

        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::get('/profile', [DriverController::class, 'profile']);
        Route::put('/profile', [DriverController::class, 'updateProfile']);
        Route::patch('/profile/fields', [DriverController::class, 'updateFields']);
        Route::get('/profile/{id}', [DriverController::class, 'show']);
        Route::post('/update-location', [DriverController::class, 'updateLocation']);
        Route::get('/check-exists/{uid}', [DriverController::class, 'checkExists']);
        Route::get('/count-by-year/{year}', [DriverController::class, 'countByYear']);

        Route::get('/customer/{id}', [CustomerController::class, 'show']);
        Route::put('/customer/{id}', [CustomerController::class, 'update']);

        Route::get('/orders/nearby', [OrderController::class, 'nearby']);
        Route::get('/orders/first-order/{userId}', [OrderController::class, 'firstOrder']);
        Route::get('/orders', [OrderController::class, 'index']);
        Route::get('/orders/{id}', [OrderController::class, 'show']);
        Route::put('/orders/{id}', [OrderController::class, 'update']);
        Route::post('/orders/{orderId}/accept', [OrderController::class, 'accept']);
        Route::get('/orders/{orderId}/accepted/{driverId}', [OrderController::class, 'getAcceptedDriver']);

        Route::get('/intercity-orders/nearby', [IntercityOrderController::class, 'nearby']);
        Route::get('/intercity-orders/first-order/{userId}', [IntercityOrderController::class, 'firstOrder']);
        Route::get('/intercity-orders/{id}', [IntercityOrderController::class, 'show']);
        Route::put('/intercity-orders/{id}', [IntercityOrderController::class, 'update']);
        Route::post('/intercity-orders/{orderId}/accept', [IntercityOrderController::class, 'accept']);
        Route::get('/intercity-orders/{orderId}/accepted/{driverId}', [IntercityOrderController::class, 'getAcceptedDriver']);

        Route::get('/documents', [DocumentController::class, 'index']);
        Route::get('/documents/{id}', [DocumentController::class, 'show']);
        Route::get('/driver-documents', [DocumentController::class, 'driverDocuments']);
        Route::get('/driver-documents/numbers', [DocumentController::class, 'documentNumbers']);
        Route::post('/driver-documents/upload', [DocumentController::class, 'upload']);

        Route::get('/services', [ServiceController::class, 'services']);
        Route::get('/vehicle-types', [ServiceController::class, 'vehicleTypes']);
        Route::get('/districts', [ServiceController::class, 'districts']);
        Route::get('/insurance-companies', [ServiceController::class, 'insuranceCompanies']);
        Route::get('/driver-rules', [ServiceController::class, 'driverRules']);

        Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
        Route::post('/wallet/transactions', [WalletController::class, 'createTransaction']);
        Route::put('/wallet/update', [WalletController::class, 'updateWallet']);
        Route::post('/wallet/razorpay/create-order', [WalletController::class, 'createRazorpayOrder']);

        Route::get('/bank-details', [BankController::class, 'show']);
        Route::put('/bank-details', [BankController::class, 'update']);
        Route::get('/bank-details/check', [BankController::class, 'check']);
        Route::post('/withdraw', [BankController::class, 'withdraw']);
        Route::get('/withdrawals', [BankController::class, 'withdrawals']);

        Route::get('/referral', [ReferralController::class, 'show']);
        Route::put('/referral', [ReferralController::class, 'update']);
        Route::post('/referral', [ReferralController::class, 'store']);
        Route::get('/referral/logs', [ReferralController::class, 'logs']);
        Route::post('/referral/update-amount', [ReferralController::class, 'updateAmount']);
        Route::post('/referral/update-intercity-amount', [ReferralController::class, 'updateIntercityAmount']);

        Route::get('/reviews', [ReviewController::class, 'index']);
        Route::post('/reviews', [ReviewController::class, 'store']);
        Route::get('/reviews/{id}', [ReviewController::class, 'show']);

        Route::post('/chat/inbox', [ChatController::class, 'inbox']);
        Route::post('/chat/message', [ChatController::class, 'sendMessage']);
        Route::get('/chat/{orderId}/messages', [ChatController::class, 'messages']);

        Route::get('/subscriptions', [SubscriptionController::class, 'index']);
        Route::get('/subscriptions/history', [SubscriptionController::class, 'history']);
        Route::post('/subscriptions/history', [SubscriptionController::class, 'createHistory']);

        Route::get('/notifications', [DocumentController::class, 'notifications']);

        Route::post('/upload-file', [DriverController::class, 'uploadFile']);
    });
});

// ═══════════════════════════════════════════════
// CUSTOMER ROUTES
// ═══════════════════════════════════════════════

use App\Http\Controllers\Api\Customer\AuthController as CustomerAuthController;
use App\Http\Controllers\Api\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Api\Customer\HomeController as CustomerHomeController;
use App\Http\Controllers\Api\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Api\Customer\IntercityOrderController as CustomerIntercityOrderController;
use App\Http\Controllers\Api\Customer\WalletController as CustomerWalletController;
use App\Http\Controllers\Api\Customer\ChatController as CustomerChatController;
use App\Http\Controllers\Api\Customer\ReviewController as CustomerReviewController;
use App\Http\Controllers\Api\Customer\ReferralController as CustomerReferralController;
use App\Http\Controllers\Api\Customer\SosController as CustomerSosController;
use App\Http\Controllers\Api\Customer\DriverController as CustomerDriverController;

Route::prefix('customer')->group(function () {

    // ── Public ──────────────────────────────────
    Route::post('/send-otp', [CustomerAuthController::class, 'sendOtp']);
    Route::post('/verify-otp', [CustomerAuthController::class, 'verifyOtp']);
    Route::post('/social-login', [CustomerAuthController::class, 'socialLogin']);

    Route::get('/settings', [CustomerHomeController::class, 'settings']);
    Route::get('/payment-settings', [CustomerHomeController::class, 'paymentSettings']);
    Route::get('/currency', [CustomerHomeController::class, 'currency']);
    Route::get('/services', [CustomerHomeController::class, 'services']);
    Route::get('/banners', [CustomerHomeController::class, 'banners']);
    Route::get('/taxes', [CustomerHomeController::class, 'taxes']);
    Route::get('/airports', [CustomerHomeController::class, 'airports']);
    Route::get('/zones', [CustomerHomeController::class, 'zones']);
    Route::get('/freight-vehicles', [CustomerHomeController::class, 'freightVehicles']);
    Route::get('/intercity-services', [CustomerHomeController::class, 'intercityServices']);
    Route::get('/onboarding', [CustomerHomeController::class, 'onboarding']);
    Route::get('/languages', [CustomerHomeController::class, 'languages']);
    Route::get('/faqs', [CustomerHomeController::class, 'faqs']);
    Route::get('/coupon/{code}', [CustomerHomeController::class, 'coupon']);

    // ── Protected ───────────────────────────────
    Route::middleware('auth:sanctum')->group(function () {

        // Auth
        Route::post('/logout', [CustomerAuthController::class, 'logout']);
        Route::delete('/delete-account', [CustomerAuthController::class, 'deleteAccount']);

        // Profile
        Route::get('/profile', [CustomerProfileController::class, 'profile']);
        Route::put('/profile', [CustomerProfileController::class, 'update']);
        Route::patch('/profile/fcm-token', [CustomerProfileController::class, 'updateFcmToken']);
        Route::get('/profile/{id}', [CustomerProfileController::class, 'show']);
        Route::post('/check-exists', [CustomerProfileController::class, 'checkExists']);
        Route::post('/upload-file', [CustomerProfileController::class, 'uploadFile']);

        // Driver info & live tracking (polling)
        Route::get('/driver/{id}', [CustomerDriverController::class, 'show']);
        Route::get('/driver/{id}/location', [CustomerDriverController::class, 'location']);
        Route::get('/driver/{id}/reviews', [CustomerReviewController::class, 'driverReviews']);

        // City Orders
        Route::post('/orders', [CustomerOrderController::class, 'store']);
        Route::get('/orders/first-order/{userId}', [CustomerOrderController::class, 'firstOrder']);
        Route::get('/orders/{id}', [CustomerOrderController::class, 'show']);
        Route::put('/orders/{id}', [CustomerOrderController::class, 'update']);
        Route::get('/orders/{id}/accepted-drivers', [CustomerOrderController::class, 'acceptedDrivers']);
        Route::get('/orders/{id}/payment-status', [CustomerOrderController::class, 'paymentStatus']);
        Route::post('/orders/referral/update-amount', [CustomerOrderController::class, 'updateReferralAmount']);

        // Intercity Orders
        Route::post('/intercity-orders', [CustomerIntercityOrderController::class, 'store']);
        Route::get('/intercity-orders/first-order/{userId}', [CustomerIntercityOrderController::class, 'firstOrder']);
        Route::get('/intercity-orders/{id}', [CustomerIntercityOrderController::class, 'show']);
        Route::put('/intercity-orders/{id}', [CustomerIntercityOrderController::class, 'update']);
        Route::get('/intercity-orders/{id}/accepted-drivers', [CustomerIntercityOrderController::class, 'acceptedDrivers']);
        Route::get('/intercity-orders/{id}/payment-status', [CustomerIntercityOrderController::class, 'paymentStatus']);
        Route::post('/intercity-orders/referral/update-amount', [CustomerIntercityOrderController::class, 'updateReferralAmount']);

        // Wallet
        Route::get('/wallet/transactions', [CustomerWalletController::class, 'transactions']);
        Route::post('/wallet/transactions', [CustomerWalletController::class, 'createTransaction']);
        Route::put('/wallet/update', [CustomerWalletController::class, 'updateWallet']);
        Route::post('/wallet/razorpay/create-order', [CustomerWalletController::class, 'createRazorpayOrder']);

        // Chat
        Route::get('/chat/inboxes', [CustomerChatController::class, 'inboxes']);
        Route::post('/chat/inbox', [CustomerChatController::class, 'inbox']);
        Route::post('/chat/message', [CustomerChatController::class, 'sendMessage']);
        Route::get('/chat/{orderId}/messages', [CustomerChatController::class, 'messages']);
        Route::post('/chat/upload-file', [CustomerChatController::class, 'uploadFile']);

        // Reviews
        Route::post('/reviews', [CustomerReviewController::class, 'store']);

        // Referral
        Route::get('/referral', [CustomerReferralController::class, 'show']);
        Route::post('/referral', [CustomerReferralController::class, 'store']);
        Route::get('/referral/check-driver-code/{code}', [CustomerReferralController::class, 'checkDriverCode']);
        Route::post('/referral/log', [CustomerReferralController::class, 'addLog']);

        // SOS
        Route::get('/sos', [CustomerSosController::class, 'index']);
        Route::post('/sos', [CustomerSosController::class, 'store']);
    });
});
