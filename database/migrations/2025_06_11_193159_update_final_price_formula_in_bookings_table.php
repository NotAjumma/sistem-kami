<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

class UpdateFinalPriceFormulaInBookingsTable extends Migration
{
    public function up(): void
    {
        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN final_price DECIMAL(10,2)
            GENERATED ALWAYS AS (total_price + service_charge - discount) STORED
        ");
    }

    public function down(): void
    {
        // Revert back to the old formula if needed
        DB::statement("
            ALTER TABLE bookings
            MODIFY COLUMN final_price DECIMAL(10,2)
            GENERATED ALWAYS AS (total_price - discount) STORED
        ");
    }
}

