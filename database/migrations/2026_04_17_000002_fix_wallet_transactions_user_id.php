<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // wallet_transactions.user_id was incorrectly constrained to driver_users.
    // Customers also create wallet transactions; user_type distinguishes them.
    public function up(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('wallet_transactions', function (Blueprint $table) {
            $table->foreign('user_id')->references('id')->on('driver_users')->nullOnDelete();
        });
    }
};
