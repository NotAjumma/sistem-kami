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
        // NO ->addColumn() or trying to add it again
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_type', 20)->nullable()->default(null)->change();
        });

    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {
            $table->string('payment_type', 20)->nullable(false)->default('unpaid')->change(); // or your previous default
        });
    }
};
