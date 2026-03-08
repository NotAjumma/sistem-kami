<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->string('payment_qr_path')->nullable()->after('fonnte_token');
        });
    }

    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn('payment_qr_path');
        });
    }
};
