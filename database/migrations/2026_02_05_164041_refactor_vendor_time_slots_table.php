<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->dropColumn([
                'package_id',
                'slot_duration_minutes',
            ]);
        });
    }

    public function down()
    {
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->unsignedBigInteger('package_id')->nullable();
            $table->unsignedInteger('slot_duration_minutes')->nullable();
        });
    }

};
