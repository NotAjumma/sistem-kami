<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE bookings_vendor_time_slot
            ADD COLUMN status VARCHAR(50) NULL AFTER booked_time_end
        ");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bookings_vendor_time_slot', function (Blueprint $table) {
            //
        });
    }
};
