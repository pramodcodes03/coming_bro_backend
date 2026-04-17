<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->string('referral_amount')->default('0')->after('referral_code');
            $table->string('total_referral_amount')->default('0')->after('referral_amount');
            $table->string('min_withdrawal_amount')->default('0')->after('total_referral_amount');
        });
    }

    public function down(): void
    {
        Schema::table('referrals', function (Blueprint $table) {
            $table->dropColumn(['referral_amount', 'total_referral_amount', 'min_withdrawal_amount']);
        });
    }
};
