<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('fishing_leaderboard_results', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fishing_leaderboard_id')->constrained()->onDelete('cascade');
            $table->foreignId('participant_id')->constrained()->onDelete('cascade');
            $table->decimal('total_weight', 6, 2);
            $table->decimal('difference', 6, 2)->nullable(); // for "closest to target" type
            $table->integer('rank');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fishing_leaderboard_results');
    }
};
