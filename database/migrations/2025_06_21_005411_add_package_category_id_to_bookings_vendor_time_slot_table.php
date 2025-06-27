<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('bookings_vendor_time_slot', function (Blueprint $table) {
            $table->foreignId('package_category_id')
                ->nullable()
                ->after('package_id')
                ->constrained('package_categories')
                ->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::table('bookings_vendor_time_slot', function (Blueprint $table) {
            $table->dropForeign(['package_category_id']);
            $table->dropColumn('package_category_id');
        });
    }
};
