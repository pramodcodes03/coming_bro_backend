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
            $table->string('id')->primary(); // Firebase UID
            $table->string('phone_number')->nullable();
            $table->string('login_type')->nullable();
            $table->string('country_code')->nullable();
            $table->string('profile_pic')->default('');
            $table->boolean('document_verification')->default(false);
            $table->string('full_name')->nullable();
            $table->boolean('is_online')->default(false);
            $table->string('service_id')->nullable();
            $table->string('fcm_token')->nullable();
            $table->string('email')->nullable();
            $table->string('city')->nullable();
            $table->string('state')->nullable();
            $table->string('group_name')->nullable();
            $table->integer('complimentary_rides')->default(0);
            $table->string('pin_code')->nullable();
            $table->string('reference_number')->nullable();
            $table->string('reference_name')->nullable();
            $table->string('gender')->nullable();
            $table->text('address')->nullable();
            $table->string('cob_number')->nullable();
            $table->string('remaining_rides')->nullable();
            $table->string('service_type')->nullable();
            $table->string('aadhar_card_number')->nullable();
            $table->string('aadhar_card_photo')->nullable();
            $table->string('pan_card_photo')->nullable();
            $table->string('pan_card_number')->nullable();
            $table->boolean('is_subscription_enable')->default(false);
            $table->string('reviews_count')->default('0.0');
            $table->string('reviews_sum')->default('0.0');
            $table->string('wallet_amount')->default('0.0');
            $table->double('location_latitude')->nullable();
            $table->double('location_longitude')->nullable();
            $table->double('rotation')->nullable();
            $table->string('position_geohash')->nullable();
            $table->double('position_latitude')->nullable();
            $table->double('position_longitude')->nullable();
            $table->timestamp('created_at')->nullable();
            $table->timestamp('subscription_expired_at')->nullable();
            $table->json('zone_ids')->nullable();
            // Vehicle Information (embedded)
            $table->string('vehicle_type')->nullable();
            $table->string('vehicle_type_id')->nullable();
            $table->timestamp('registration_date')->nullable();
            $table->string('vehicle_registration_date')->nullable();
            $table->string('vehicle_color')->nullable();
            $table->string('vehicle_number')->nullable();
            $table->string('company_name')->nullable();
            $table->string('vehicle_model')->nullable();
            $table->string('rc_number')->nullable();
            $table->string('engine_number')->nullable();
            $table->string('registration_type')->nullable();
            $table->string('vehicle_fuel')->nullable();
            $table->string('permit_photo')->nullable();
            $table->string('permit_number')->nullable();
            $table->string('rc_image')->nullable();
            $table->string('chassis_number')->nullable();
            $table->string('district')->nullable();
            $table->string('district_id')->nullable();
            $table->string('ac_perkm_rate')->nullable();
            $table->string('non_ac_perkm_rate')->nullable();
            $table->string('selfie_photo')->nullable();
            $table->string('vehicle_manufacture_date')->nullable();
            $table->string('seats')->nullable();
            $table->boolean('carrier')->default(false);
            $table->json('driver_rules')->nullable();
            // Insurance Details (embedded)
            $table->string('agent_code')->nullable();
            $table->string('agent_name')->nullable();
            $table->string('insurance_company')->nullable();
            $table->string('insurance_type')->nullable();
            $table->string('insurance_amount')->nullable();
            $table->string('insurance_expiry_date')->nullable();
            $table->string('insurance_number')->nullable();
            $table->string('insurance_premium')->nullable();
            $table->string('nominee_age')->nullable();
            $table->string('nominee_name')->nullable();
            $table->string('nominee_number')->nullable();
            $table->string('nominee_relation')->nullable();
            $table->string('paid_receipt_number')->nullable();
            $table->string('payment_date')->nullable();
            $table->string('payment_type')->nullable();
            // Subscription Information (embedded)
            $table->string('subscription_gst_amount')->nullable();
            $table->string('subscription_id')->nullable();
            $table->timestamp('subscription_date')->nullable();
            $table->timestamp('subscription_end_date')->nullable();
            $table->timestamp('subscription_start_date')->nullable();
            $table->string('subscription_amount')->nullable();
            $table->string('subscription_role')->nullable();
            $table->string('subscription_remaining_days')->nullable();
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
