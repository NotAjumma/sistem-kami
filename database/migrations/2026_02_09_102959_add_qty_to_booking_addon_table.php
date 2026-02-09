<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_addon', function (Blueprint $table) {
            $table->unsignedInteger('qty')->default(1)->after('addon_id');
        });
    }

    public function down(): void
    {
        Schema::table('booking_addon', function (Blueprint $table) {
            $table->dropColumn('qty');
        });
    }
};
