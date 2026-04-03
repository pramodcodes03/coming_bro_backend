<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            // Geographic data
            StateSeeder::class,
            CitySeeder::class,
            DistrictSeeder::class,
            ZoneSeeder::class,
            AirportSeeder::class,

            // Services & Pricing
            ServiceSeeder::class,
            IntercityServiceSeeder::class,
            FreightVehicleSeeder::class,
            RateSettingSeeder::class,

            // Vehicles
            VehicleCompanySeeder::class,
            VehicleModelSeeder::class,
            VehicleTypeSeeder::class,
            FuelTypeSeeder::class,

            // System configuration
            CurrencySeeder::class,
            LanguageSeeder::class,
            TaxSeeder::class,
            DocumentSeeder::class,
            DriverRuleSeeder::class,
            InsuranceCompanySeeder::class,
            SubscriptionPlanSeeder::class,

            // Settings
            SettingsSeeder::class,

            // Content
            CmsPageSeeder::class,
            BannerSeeder::class,
            OnBoardingSeeder::class,
            FaqSeeder::class,
            CouponSeeder::class,
        ]);
    }
}
