<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_addons', function (Blueprint $table) {

            // Drop old column if exists
            if (Schema::hasColumn('addons', 'special_date')) {
                $table->dropColumn('special_date');
            }

            // Add new range columns
            $table->date('special_date_start')->nullable()->after('time_minutes');
            $table->date('special_date_end')->nullable()->after('special_date_start');
        });
    }

    public function down(): void
    {
        Schema::table('package_addons', function (Blueprint $table) {

            $table->dropColumn(['special_date_start', 'special_date_end']);

            // Optional: restore old column if rollback
            $table->date('special_date')->nullable();
        });
    }
};
