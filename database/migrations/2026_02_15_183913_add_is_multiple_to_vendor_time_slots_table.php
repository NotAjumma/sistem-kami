<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {

            $table->integer('rest_minutes')
                ->default(0)
                ->after('duration_minutes');

            $table->boolean('is_multiple')
                ->default(false)
                ->after('rest_minutes');

        });
    }

    public function down(): void
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {

            $table->dropColumn(['rest_minutes', 'is_multiple']);

        });
    }

};
