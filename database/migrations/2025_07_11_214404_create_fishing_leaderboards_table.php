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
        Schema::create('fishing_leaderboards', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Monthly Match July 2025"
            $table->foreignId('fishing_rank_id')->constrained()->onDelete('cascade');
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fishing_leaderboards');
    }
};
