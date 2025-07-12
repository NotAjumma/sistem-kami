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
        Schema::table('fishing_leaderboards', function (Blueprint $table) {
            $table->foreignId('event_id')
                ->after('fishing_rank_id')
                ->nullable()
                ->constrained('events')
                ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('fishing_leaderboards', function (Blueprint $table) {
            $table->dropForeign(['event_id']);
            $table->dropColumn('event_id');
        });
    }
};
