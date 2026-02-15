<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->string('pax')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->integer('pax')->nullable()->change();
        });
    }
};
