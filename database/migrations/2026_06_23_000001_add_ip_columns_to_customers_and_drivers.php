<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Capture the IP a customer/driver registered from and the IP of their
    // most recent login, so the admin panel can filter/audit accounts by IP
    // (e.g. spotting multiple accounts created from a single device/network).
    public function up(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->string('register_ip', 45)->nullable()->after('phone_number');
            $table->string('last_login_ip', 45)->nullable()->after('register_ip');
        });

        Schema::table('driver_users', function (Blueprint $table) {
            $table->string('register_ip', 45)->nullable()->after('phone_number');
            $table->string('last_login_ip', 45)->nullable()->after('register_ip');
        });
    }

    public function down(): void
    {
        Schema::table('customers', function (Blueprint $table) {
            $table->dropColumn(['register_ip', 'last_login_ip']);
        });

        Schema::table('driver_users', function (Blueprint $table) {
            $table->dropColumn(['register_ip', 'last_login_ip']);
        });
    }
};
