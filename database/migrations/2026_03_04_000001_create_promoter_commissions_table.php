<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('promoter_commissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('commission_value', 10, 2)->default(0);
            $table->timestamps();

            $table->unique(['worker_id'], 'unique_promoter_worker');
        });
    }

    public function down()
    {
        Schema::dropIfExists('promoter_commissions');
    }
};
