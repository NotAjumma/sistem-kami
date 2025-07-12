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
        Schema::table('fishing_catches', function (Blueprint $table) {
            $table->foreignId('fishing_leaderboard_id')->nullable()->after('participant_id')->constrained('fishing_leaderboards')->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('fishing_catches', function (Blueprint $table) {
            $table->dropForeign(['fishing_leaderboard_id']);
            $table->dropColumn('fishing_leaderboard_id');
        });
    }

};
