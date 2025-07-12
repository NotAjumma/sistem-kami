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
        Schema::create('fishing_ranks', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // e.g. "Heaviest", "Closest to Target"
            $table->enum('type', ['heaviest', 'closest_to_target']);
            $table->decimal('target_weight', 6, 2)->nullable(); // only for closest_to_target
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('fishing_ranks');
    }
};
