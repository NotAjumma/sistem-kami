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
        Schema::create('ticket_input_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ticket_input_id')->constrained('ticket_inputs')->onDelete('cascade');
            $table->foreignId('ticket_id')->constrained()->onDelete('cascade');
            $table->foreignId('booking_id')->constrained()->onDelete('cascade'); // Or booking_id, based on your system
            $table->text('answer'); // string or JSON if multi-value
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('ticket_input_details');
    }
};
