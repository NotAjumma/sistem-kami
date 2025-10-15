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
        Schema::table('bookings_vendor_time_slot', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_time_slot_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('bookings_vendor_time_slot', function (Blueprint $table) {
            $table->unsignedBigInteger('vendor_time_slot_id')->nullable(false)->change();
        });
    }
};
