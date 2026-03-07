<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('package_related_packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('related_package_id')->constrained('packages')->onDelete('cascade');
            $table->timestamps();

            $table->unique(['package_id', 'related_package_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_related_packages');
    }
};
