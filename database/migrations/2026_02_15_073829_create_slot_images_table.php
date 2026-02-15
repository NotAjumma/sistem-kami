<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('slot_images', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('slot_id');
            $table->string('url');
            $table->string('alt_text')->nullable();
            $table->boolean('is_cover')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // $table->foreign('slot_id')->references('id')->on('slots')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('slot_images');
    }
};
