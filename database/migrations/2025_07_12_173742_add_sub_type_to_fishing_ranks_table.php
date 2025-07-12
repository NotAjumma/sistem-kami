<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('fishing_ranks', function (Blueprint $table) {
            $table->enum('sub_type', ['heaviest', 'most_catch', 'other'])->nullable()->after('type');
        });
    }

    public function down()
    {
        Schema::table('fishing_ranks', function (Blueprint $table) {
            $table->dropColumn('sub_type');
        });
    }
};
