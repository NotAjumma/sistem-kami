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
        Schema::create('package_inputs', function (Blueprint $table) {
            $table->id(); // id bigint unsigned auto_increment primary key
            $table->unsignedBigInteger('package_id'); // foreign key reference
            $table->string('parent')->nullable();
            $table->string('label');
            $table->string('input_key');
            $table->string('input_type');
            $table->json('options')->nullable();
            $table->string('placeholder')->nullable();
            $table->boolean('is_required')->default(false);
            $table->integer('sort_order')->default(0);
            $table->timestamps();

            // Add foreign key constraint if needed
            $table->foreign('package_id')->references('id')->on('packages')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_inputs');
    }
};
