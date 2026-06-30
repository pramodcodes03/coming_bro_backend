<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    // Admin-managed ride cancellation reasons, shown on the customer & driver
    // cancel sheets (fetched via the API) instead of being hardcoded in the apps.
    public function up(): void
    {
        Schema::create('cancel_reasons', function (Blueprint $table) {
            $table->id();
            $table->string('reason');
            $table->string('applies_to')->default('both'); // customer | driver | both
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cancel_reasons');
    }
};
