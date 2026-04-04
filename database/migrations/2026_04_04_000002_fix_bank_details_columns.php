<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bank_details', function (Blueprint $table) {
            // Rename holder_name to account_holder_name for consistency
            if (Schema::hasColumn('bank_details', 'holder_name')) {
                $table->renameColumn('holder_name', 'account_holder_name');
            }

            // Add ifsc_code column if not exists
            if (!Schema::hasColumn('bank_details', 'ifsc_code')) {
                $table->string('ifsc_code')->nullable()->after('account_number');
            }
        });
    }

    public function down(): void
    {
        Schema::table('bank_details', function (Blueprint $table) {
            if (Schema::hasColumn('bank_details', 'account_holder_name')) {
                $table->renameColumn('account_holder_name', 'holder_name');
            }
            if (Schema::hasColumn('bank_details', 'ifsc_code')) {
                $table->dropColumn('ifsc_code');
            }
        });
    }
};
