<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // GST tracking per recharge. Each recharge plan carries the GST % that
    // applies to it, and every recharge wallet transaction stores the resolved
    // base / GST / total breakdown so collected GST can be reported and the
    // payment register can be exported for accounting.
    public function up(): void
    {
        Schema::table('recharge_plans', function (Blueprint $table) {
            $table->decimal('gst_percent', 5, 2)->default(0)->after('discount_pct');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->unsignedBigInteger('recharge_plan_id')->nullable()->after('user_id');
            $table->decimal('base_amount', 10, 2)->nullable()->after('amount');
            $table->decimal('gst_percent', 5, 2)->nullable()->after('base_amount');
            $table->decimal('gst_amount', 10, 2)->nullable()->after('gst_percent');
            $table->decimal('total_amount', 10, 2)->nullable()->after('gst_amount');
        });
    }

    public function down(): void
    {
        Schema::table('recharge_plans', function (Blueprint $table) {
            $table->dropColumn('gst_percent');
        });

        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropColumn(['recharge_plan_id', 'base_amount', 'gst_percent', 'gst_amount', 'total_amount']);
        });
    }
};
