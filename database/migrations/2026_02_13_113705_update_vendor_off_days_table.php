<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_off_days', function (Blueprint $table) {
            // Make off_date nullable for weekly off
            $table->date('off_date')->nullable()->change();

            // Add day_of_week for weekly off (1 = Mon, 7 = Sun)
            if (!Schema::hasColumn('vendor_off_days', 'day_of_week')) {
                $table->tinyInteger('day_of_week')->nullable()->after('off_date');
            }
        });
    }

    public function down(): void
    {
        Schema::table('vendor_off_days', function (Blueprint $table) {
            // Remove unique constraint
            $table->dropUnique('unique_off_time');

            // Drop day_of_week column
            if (Schema::hasColumn('vendor_off_days', 'day_of_week')) {
                $table->dropColumn('day_of_week');
            }

            // Make off_date NOT NULL again
            $table->date('off_date')->nullable(false)->change();
        });
    }
};
