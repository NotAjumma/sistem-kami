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
        Schema::table('organizers', function (Blueprint $table) {
            $table->string('office_name')->nullable()->after('address_line2');
            $table->decimal('latitude', 10, 7)->nullable()->after('country');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');
        });
    }

    public function down()
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn(['office_name', 'latitude', 'longitude']);
        });
    }
};
