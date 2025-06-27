<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('package_input_answers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('package_input_id');
            $table->unsignedBigInteger('package_id');
            $table->unsignedBigInteger('booking_id');
            $table->text('answer')->nullable();
            $table->timestamps();

            // Foreign key constraints (optional, add if models exist)
            $table->foreign('package_input_id')->references('id')->on('package_inputs')->onDelete('cascade');
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
            $table->foreign('booking_id')->references('id')->on('bookings')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_input_answers');
    }
};
