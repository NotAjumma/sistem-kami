<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wallet_transactions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('organizer_id')
                ->constrained()
                ->cascadeOnDelete();

            $table->string('type'); 
            // income | commission | worker_payment | withdrawal | refund

            $table->decimal('amount', 12, 2);

            $table->decimal('balance_before', 12, 2)->default(0);
            $table->decimal('balance_after', 12, 2)->default(0);

            $table->string('reference_type')->nullable();
            // booking | payout | manual

            $table->unsignedBigInteger('reference_id')->nullable();

            $table->text('description')->nullable();

            $table->string('status')->default('completed');
            // pending | completed | rejected

            $table->timestamps();

            $table->index(['organizer_id']);
            $table->index(['reference_type','reference_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wallet_transactions');
    }
};
