<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        // Update payments table
        Schema::table('payments', function (Blueprint $table) {
            $table->string('payment_type', 20)->nullable()->after('amount'); // deposit, full, balance
            $table->string('method', 50)->nullable()->after('payment_type'); // online, manual, etc
            $table->boolean('is_final')->default(false)->after('payment_type');
        });

        // Update bookings table
        Schema::table('bookings', function (Blueprint $table) {
            $table->decimal('paid_amount', 10, 2)->default(0)->after('final_price');
            $table->string('payment_type', 20)->nullable()->after('paid_amount'); // unpaid, partial, paid
        });

        // Optional: booking_payment_schedules table
        Schema::create('booking_payment_schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained('bookings')->onDelete('cascade');
            $table->string('booking_code', 100);
            $table->foreignId('package_id')->constrained('packages')->onDelete('cascade');
            $table->foreignId('organizer_id')->constrained('organizers')->onDelete('cascade');
            $table->date('due_date');
            $table->decimal('amount', 10, 2);
            $table->string('payment_type', 20); // deposit, balance, full
            $table->boolean('is_paid')->default(false);
            $table->timestamp('paid_at')->nullable();
            $table->timestamps();

             // Optional: add foreign key manually if your DB supports it and booking_code is unique
            $table->foreign('booking_code')
                ->references('booking_code')
                ->on('bookings')
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['payment_type', 'method', 'is_final']);
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->dropColumn(['paid_amount', 'payment_type']);
        });

        Schema::dropIfExists('booking_payment_schedules');
    }
};
