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
        Schema::table('packages', function (Blueprint $table) {
            $table->string('last_paid_date', 50)->nullable()->after('valid_until'); // e.g., "1 week", "2 day"
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['last_paid_date']);
        });
    }
};
