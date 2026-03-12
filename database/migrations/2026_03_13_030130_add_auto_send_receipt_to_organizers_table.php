<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->boolean('auto_send_receipt')->default(true)->after('fonnte_token');
        });
    }

    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn('auto_send_receipt');
        });
    }
};
