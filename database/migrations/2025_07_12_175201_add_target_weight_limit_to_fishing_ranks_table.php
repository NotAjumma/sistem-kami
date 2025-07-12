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
            $table->enum('target_weight_limit', ['above', 'below', 'both'])->default('both')->after('target_weight');
        });
    }

    public function down()
    {
        Schema::table('fishing_ranks', function (Blueprint $table) {
            $table->dropColumn('target_weight_limit');
        });
    }

};
