<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->integer('pax')
                  ->nullable()
                  ->after('slot_price');
        });
    }

    public function down(): void
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->dropColumn('pax');
        });
    }
};
