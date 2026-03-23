<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('driver_users', function (Blueprint $table) {
            $table->string('id', 128)->primary(); // Firebase UID
            $table->string('phone_number', 20)->nullable();
            $table->string('login_type', 30)->nullable();
            $table->string('country_code', 10)->nullable();
            $table->string('profile_pic', 500)->default('');
            $table->boolean('document_verification')->default(false);
            $table->string('full_name', 100)->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('service_id', 50)->nullable();
            $table->text('fcm_token')->nullable();
            $table->string('email', 100)->nullable();
            $table->string('city', 100)->nullable();
            $table->string('state', 100)->nullable();
            $table->string('group_name', 100)->nullable();
            $table->integer('complimentary_rides')->default(0);
            $table->string('pin_code', 10)->nullable();
            $table->string('reference_number', 50)->nullable();
            $table->string('reference_name', 100)->nullable();
            $table->string('gender', 20)->nullable();
            $table->text('address')->nullable();
            $table->string('cob_number', 50)->nullable();
            $table->string('remaining_rides', 20)->nullable();
            $table->string('service_type', 50)->nullable();
            $table->string('aadhar_card_number', 20)->nullable();
            $table->string('aadhar_card_photo', 500)->nullable();
            $table->string('pan_card_photo', 500)->nullable();
            $table->string('pan_card_number', 20)->nullable();
            $table->boolean('is_subscription_enable')->default(false);
            $table->string('reviews_count', 20)->default('0.0');
            $table->string('reviews_sum', 20)->default('0.0');
            $table->string('wallet_amount', 20)->default('0.0');
            $table->double('location_latitude')->nullable();
            $table->double('location_longitude')->nullable();
            $table->double('rotation')->nullable();
            $table->string('position_geohash', 20)->nullable();
            $table->double('position_latitude')->nullable();
            $table->double('position_longitude')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('subscription_expired_at')->nullable();
            $table->json('zone_ids')->nullable();
            // Vehicle Information (embedded)
            $table->string('vehicle_type', 50)->nullable();
            $table->string('vehicle_type_id', 50)->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->string('vehicle_registration_date', 30)->nullable();
            $table->string('vehicle_color', 30)->nullable();
            $table->string('vehicle_number', 30)->nullable();
            $table->string('company_name', 100)->nullable();
            $table->string('vehicle_model', 100)->nullable();
            $table->string('rc_number', 50)->nullable();
            $table->string('engine_number', 50)->nullable();
            $table->string('registration_type', 30)->nullable();
            $table->string('vehicle_fuel', 30)->nullable();
            $table->string('permit_photo', 500)->nullable();
            $table->string('permit_number', 50)->nullable();
            $table->string('rc_image', 500)->nullable();
            $table->string('chassis_number', 50)->nullable();
            $table->string('district', 100)->nullable();
            $table->string('district_id', 50)->nullable();
            $table->string('ac_perkm_rate', 20)->nullable();
            $table->string('non_ac_perkm_rate', 20)->nullable();
            $table->string('selfie_photo', 500)->nullable();
            $table->string('vehicle_manufacture_date', 30)->nullable();
            $table->string('seats', 10)->nullable();
            $table->boolean('carrier')->default(false);
            $table->json('driver_rules')->nullable();
            // Insurance Details (embedded)
            $table->string('agent_code', 50)->nullable();
            $table->string('agent_name', 100)->nullable();
            $table->string('insurance_company', 100)->nullable();
            $table->string('insurance_type', 50)->nullable();
            $table->string('insurance_amount', 20)->nullable();
            $table->string('insurance_expiry_date', 30)->nullable();
            $table->string('insurance_number', 50)->nullable();
            $table->string('insurance_premium', 20)->nullable();
            $table->string('nominee_age', 10)->nullable();
            $table->string('nominee_name', 100)->nullable();
            $table->string('nominee_number', 20)->nullable();
            $table->string('nominee_relation', 50)->nullable();
            $table->string('paid_receipt_number', 50)->nullable();
            $table->string('payment_date', 30)->nullable();
            $table->string('payment_type', 30)->nullable();
            // Subscription Information (embedded)
            $table->string('subscription_gst_amount', 20)->nullable();
            $table->string('subscription_id', 50)->nullable();
            $table->timestamp('subscription_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            $table->timestamp('subscription_start_date')->nullable();
            $table->string('subscription_amount', 20)->nullable();
            $table->string('subscription_role', 30)->nullable();
            $table->string('subscription_remaining_days', 20)->nullable();
            $table->json('subscription_plan_data')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('driver_users');
    }
};
