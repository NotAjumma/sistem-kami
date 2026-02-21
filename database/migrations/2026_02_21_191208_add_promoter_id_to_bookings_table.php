<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->foreignId('promoter_id')
                ->nullable()
                ->after('organizer_id')
                ->constrained('workers')
                ->nullOnDelete();

        });
    }

    public function down(): void
    {
        Schema::table('bookings', function (Blueprint $table) {

            $table->dropForeign(['promoter_id']);
            $table->dropColumn('promoter_id');

        });
    }
};
