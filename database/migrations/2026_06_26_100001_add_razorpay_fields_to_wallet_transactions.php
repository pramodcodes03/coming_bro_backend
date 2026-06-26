<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Persist the verified Razorpay references against every gateway-backed
    // wallet/recharge transaction. The unique payment id makes fulfilment
    // idempotent — the same captured payment can never unlock a recharge twice.
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->string('razorpay_order_id')->nullable()->after('transaction_id');
            $table->string('razorpay_payment_id')->nullable()->after('razorpay_order_id');
            $table->unique('razorpay_payment_id', 'wallet_tx_rzp_payment_unique');
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropUnique('wallet_tx_rzp_payment_unique');
            $table->dropColumn(['razorpay_order_id', 'razorpay_payment_id']);
        });
    }
};
