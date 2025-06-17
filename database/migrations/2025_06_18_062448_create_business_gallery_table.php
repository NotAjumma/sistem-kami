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
        Schema::create('business_gallery', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('organizer_id'); // FK to organizer
            $table->string('file_name');               // Stored image filename
            $table->string('alt_text')->nullable();    // Image alt text for SEO
            $table->boolean('is_cover')->default(false); // Cover image?
            $table->unsignedSmallInteger('sort_order')->default(0); // Order of appearance
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('organizer_id')
                ->references('id')->on('organizers')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('business_gallery');
    }
};
