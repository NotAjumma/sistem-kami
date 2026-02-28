<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_form_fields', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizer_id')
                ->constrained()
                ->onDelete('cascade');

            $table->string('field_key');      // pax, theme, guest_count
            $table->string('field_label');    // "How Many People?"
            $table->string('field_type');     // text, number, select, checkbox

            $table->boolean('is_required')->default(false);

            $table->json('options')->nullable(); 
            // for select type: ["Galaxy","Elegant","Rustic"]

            $table->timestamps();

            $table->index(['organizer_id', 'field_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('booking_form_fields');
    }
};
