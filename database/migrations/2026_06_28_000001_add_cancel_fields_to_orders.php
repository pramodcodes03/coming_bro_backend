<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Record why a ride was cancelled (chosen reason + optional free text),
    // who cancelled it, and when — so the admin panel can show cancellation
    // reasons and the team can spot patterns (driver no-shows, cash demands…).
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->string('cancel_reason')->nullable()->after('status');
            $table->string('cancelled_by', 20)->nullable()->after('cancel_reason'); // customer | driver | admin
            $table->timestamp('cancelled_at')->nullable()->after('cancelled_by');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['cancel_reason', 'cancelled_by', 'cancelled_at']);
        });
    }
};
