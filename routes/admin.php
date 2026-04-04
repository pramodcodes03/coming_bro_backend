<?php

use App\Http\Controllers\Admin\AirportController;
use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\CityController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\CouponController;
use App\Http\Controllers\Admin\CurrencyController;
use App\Http\Controllers\Admin\CustomerController;
use App\Http\Controllers\Admin\DistrictController;
use App\Http\Controllers\Admin\DocumentController;
use App\Http\Controllers\Admin\DriverController;
use App\Http\Controllers\Admin\DriverRuleController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\FreightVehicleController;
use App\Http\Controllers\Admin\FuelTypeController;
use App\Http\Controllers\Admin\InsuranceCompanyController;
use App\Http\Controllers\Admin\IntercityOrderController;
use App\Http\Controllers\Admin\IntercityServiceController;
use App\Http\Controllers\Admin\LanguageController;
use App\Http\Controllers\Admin\OnboardingController;
use App\Http\Controllers\Admin\OrderController;
use App\Http\Controllers\Admin\PayoutRequestController;
use App\Http\Controllers\Admin\RechargePlanController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\SosController;
use App\Http\Controllers\Admin\StateController;
use App\Http\Controllers\Admin\SubscriptionPlanController;
use App\Http\Controllers\Admin\TaxController;
use App\Http\Controllers\Admin\VehicleCompanyController;
use App\Http\Controllers\Admin\VehicleModelController;
use App\Http\Controllers\Admin\VehicleTypeController;
use App\Http\Controllers\Admin\WalletTransactionController;
use App\Http\Controllers\Admin\ZoneController;
use Illuminate\Support\Facades\Route;

// Admin Guest Routes
Route::middleware('guest:admin')->group(function () {
    Route::get('/', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::get('/login', [AuthController::class, 'showLoginForm'])->name('admin.login.form');
    Route::post('/login', [AuthController::class, 'login'])->name('admin.login.submit');
});

// Admin Authenticated Routes
Route::middleware('auth:admin')->group(function () {
    Route::get('/dashboard', [AuthController::class, 'dashboard'])->name('admin.dashboard');
    Route::get('/logout', [AuthController::class, 'logout'])->name('admin.logout');

    // Customers
    Route::resource('customers', CustomerController::class)->names('admin.customers');
    Route::patch('customers/{customer}/toggle-status', [CustomerController::class, 'toggleStatus'])->name('admin.customers.toggle-status');

    // Drivers
    Route::get('drivers', [DriverController::class, 'index'])->name('admin.drivers.index');
    Route::get('drivers/{driver}', [DriverController::class, 'view'])->name('admin.drivers.view');
    Route::get('drivers/{driver}/edit', [DriverController::class, 'edit'])->name('admin.drivers.edit');
    Route::put('drivers/{driver}', [DriverController::class, 'update'])->name('admin.drivers.update');
    Route::delete('drivers/{driver}', [DriverController::class, 'destroy'])->name('admin.drivers.destroy');
    Route::patch('drivers/{driver}/toggle-status', [DriverController::class, 'toggleStatus'])->name('admin.drivers.toggle-status');

    // Driver Documents
    Route::resource('driver-documents', DocumentController::class)->names('admin.driver-documents');
    Route::patch('driver-documents/{driver_document}/toggle-status', [DocumentController::class, 'toggleStatus'])->name('admin.driver-documents.toggle-status');

    // Driver Rules
    Route::resource('driver-rules', DriverRuleController::class)->names('admin.driver-rules');
    Route::patch('driver-rules/{driver_rule}/toggle-status', [DriverRuleController::class, 'toggleStatus'])->name('admin.driver-rules.toggle-status');

    // City Orders
    Route::get('orders', [OrderController::class, 'index'])->name('admin.orders.index');
    Route::get('orders/{order}', [OrderController::class, 'show'])->name('admin.orders.show');
    Route::patch('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('admin.orders.update-status');

    // Intercity Orders
    Route::get('intercity-orders', [IntercityOrderController::class, 'index'])->name('admin.intercity-orders.index');
    Route::get('intercity-orders/{order}', [IntercityOrderController::class, 'show'])->name('admin.intercity-orders.show');
    Route::patch('intercity-orders/{order}/status', [IntercityOrderController::class, 'updateStatus'])->name('admin.intercity-orders.update-status');

    // Services
    Route::resource('services', ServiceController::class)->names('admin.services');
    Route::patch('services/{service}/toggle-status', [ServiceController::class, 'toggleStatus'])->name('admin.services.toggle-status');

    // Intercity Services
    Route::resource('intercity-services', IntercityServiceController::class)->names('admin.intercity-services');
    Route::patch('intercity-services/{intercity_service}/toggle-status', [IntercityServiceController::class, 'toggleStatus'])->name('admin.intercity-services.toggle-status');

    // Vehicle Types
    Route::resource('vehicle-types', VehicleTypeController::class)->names('admin.vehicle-types');
    Route::patch('vehicle-types/{vehicle_type}/toggle-status', [VehicleTypeController::class, 'toggleStatus'])->name('admin.vehicle-types.toggle-status');

    // Vehicle Companies
    Route::resource('vehicle-companies', VehicleCompanyController::class)->names('admin.vehicle-companies');
    Route::patch('vehicle-companies/{vehicle_company}/toggle-status', [VehicleCompanyController::class, 'toggleStatus'])->name('admin.vehicle-companies.toggle-status');

    // Vehicle Models
    Route::resource('vehicle-models', VehicleModelController::class)->names('admin.vehicle-models');
    Route::patch('vehicle-models/{vehicle_model}/toggle-status', [VehicleModelController::class, 'toggleStatus'])->name('admin.vehicle-models.toggle-status');

    // Insurance Companies
    Route::resource('insurance-companies', InsuranceCompanyController::class)->names('admin.insurance-companies');

    // Freight Vehicles
    Route::resource('freight-vehicles', FreightVehicleController::class)->names('admin.freight-vehicles');
    Route::patch('freight-vehicles/{freight_vehicle}/toggle-status', [FreightVehicleController::class, 'toggleStatus'])->name('admin.freight-vehicles.toggle-status');

    // Zones
    Route::resource('zones', ZoneController::class)->names('admin.zones');
    Route::patch('zones/{zone}/toggle-status', [ZoneController::class, 'toggleStatus'])->name('admin.zones.toggle-status');

    // States
    Route::resource('states', StateController::class)->names('admin.states');
    Route::patch('states/{state}/toggle-status', [StateController::class, 'toggleStatus'])->name('admin.states.toggle-status');

    // Cities
    Route::resource('cities', CityController::class)->names('admin.cities');
    Route::patch('cities/{city}/toggle-status', [CityController::class, 'toggleStatus'])->name('admin.cities.toggle-status');

    // Districts
    Route::resource('districts', DistrictController::class)->names('admin.districts');
    Route::patch('districts/{district}/toggle-status', [DistrictController::class, 'toggleStatus'])->name('admin.districts.toggle-status');

    // Airports
    Route::resource('airports', AirportController::class)->names('admin.airports');
    Route::patch('airports/{airport}/toggle-status', [AirportController::class, 'toggleStatus'])->name('admin.airports.toggle-status');

    // Taxes
    Route::resource('taxes', TaxController::class)->names('admin.taxes');
    Route::patch('taxes/{tax}/toggle-status', [TaxController::class, 'toggleStatus'])->name('admin.taxes.toggle-status');

    // Coupons
    Route::resource('coupons', CouponController::class)->names('admin.coupons');
    Route::patch('coupons/{coupon}/toggle-status', [CouponController::class, 'toggleStatus'])->name('admin.coupons.toggle-status');

    // Currencies
    Route::resource('currencies', CurrencyController::class)->names('admin.currencies');
    Route::patch('currencies/{currency}/toggle-status', [CurrencyController::class, 'toggleStatus'])->name('admin.currencies.toggle-status');

    // Subscription Plans
    Route::resource('subscription-plans', SubscriptionPlanController::class)->names('admin.subscription-plans');
    Route::patch('subscription-plans/{subscription_plan}/toggle-status', [SubscriptionPlanController::class, 'toggleStatus'])->name('admin.subscription-plans.toggle-status');

    // Recharge Plans
    Route::resource('recharge-plans', RechargePlanController::class)->names('admin.recharge-plans');
    Route::patch('recharge-plans/{recharge_plan}/toggle-status', [RechargePlanController::class, 'toggleStatus'])->name('admin.recharge-plans.toggle-status');

    // Banners
    Route::resource('banners', BannerController::class)->names('admin.banners');
    Route::patch('banners/{banner}/toggle-status', [BannerController::class, 'toggleStatus'])->name('admin.banners.toggle-status');

    // CMS Pages
    Route::resource('cms', CmsController::class)->names('admin.cms');
    Route::patch('cms/{cm}/toggle-status', [CmsController::class, 'toggleStatus'])->name('admin.cms.toggle-status');

    // FAQs
    Route::resource('faqs', FaqController::class)->names('admin.faqs');
    Route::patch('faqs/{faq}/toggle-status', [FaqController::class, 'toggleStatus'])->name('admin.faqs.toggle-status');

    // Onboarding
    Route::resource('onboarding', OnboardingController::class)->names('admin.onboarding');
    Route::patch('onboarding/{onboarding}/toggle-status', [OnboardingController::class, 'toggleStatus'])->name('admin.onboarding.toggle-status');

    // Fuel Types
    Route::resource('fuel-types', FuelTypeController::class)->names('admin.fuel-types');
    Route::patch('fuel-types/{fuel_type}/toggle-status', [FuelTypeController::class, 'toggleStatus'])->name('admin.fuel-types.toggle-status');

    // Languages
    Route::resource('languages', LanguageController::class)->names('admin.languages');
    Route::patch('languages/{language}/toggle-status', [LanguageController::class, 'toggleStatus'])->name('admin.languages.toggle-status');

    // Payout Requests
    Route::get('payout-requests', [PayoutRequestController::class, 'index'])->name('admin.payout-requests.index');
    Route::patch('payout-requests/{id}/status', [PayoutRequestController::class, 'updateStatus'])->name('admin.payout-requests.update-status');

    // Wallet Transactions
    Route::get('wallet/driver', [WalletTransactionController::class, 'driverTransactions'])->name('admin.wallet.driver');
    Route::get('wallet/user', [WalletTransactionController::class, 'userTransactions'])->name('admin.wallet.user');

    // SOS
    Route::get('sos', [SosController::class, 'index'])->name('admin.sos.index');
    Route::post('sos', [SosController::class, 'store'])->name('admin.sos.store');

    // Settings
    Route::get('/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
    Route::put('/settings/{key}', [SettingsController::class, 'update'])->name('admin.settings.update');
});
