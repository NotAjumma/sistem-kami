<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePaymentsTable extends Migration
{
    public function up()
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();

            $table->foreignId('booking_id')->constrained()->onDelete('cascade');

            $table->string('bill_code')->unique(); // dari ToyyibPay
            $table->string('ref_no')->nullable();  // optional ref

            $table->decimal('amount', 10, 2);      // jumlah bayaran
            $table->enum('status', ['pending', 'paid', 'failed'])->default('pending');
            $table->timestamp('paid_at')->nullable(); // bila selesai bayar

            $table->json('raw_response')->nullable(); // optionally simpan full callback

            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('payments');
    }
}
