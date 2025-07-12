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
        Schema::table('fishing_leaderboard_results', function (Blueprint $table) {
            $table->timestamp('caught_at')->nullable()->after('difference');
        });
    }

    public function down()
    {
        Schema::table('fishing_leaderboard_results', function (Blueprint $table) {
            $table->dropColumn('caught_at');
        });
    }
};
