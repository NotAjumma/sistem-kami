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
            ADD COLUMN booked_date_start DATE NULL AFTER vendor_time_slot_id,
            ADD COLUMN booked_date_end DATE NULL AFTER booked_date_start,
            ADD COLUMN package_id BIGINT UNSIGNED NULL AFTER booked_date_end,
            ADD COLUMN organizer_id BIGINT UNSIGNED NULL AFTER package_id,
            ADD CONSTRAINT fk_bvts_package_id FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
            ADD CONSTRAINT fk_bvts_organizer_id FOREIGN KEY (organizer_id) REFERENCES organizers(id) ON DELETE CASCADE
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
