<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_form_fields', function (Blueprint $table) {

            // Drop old foreign key first
            $table->dropForeign(['organizer_id']);
            $table->dropColumn('organizer_id');

            // Add package_id
            $table->foreignId('package_id')
                ->after('id')
                ->constrained('packages') // change if your table name different
                ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::table('booking_form_fields', function (Blueprint $table) {

            $table->dropForeign(['package_id']);
            $table->dropColumn('package_id');

            $table->foreignId('organizer_id')
                ->constrained()
                ->onDelete('cascade');
        });
    }
};
