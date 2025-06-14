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
        Schema::table('ticket_inputs', function (Blueprint $table) {
            $table->string('parent')->nullable()->after('ticket_id');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_inputs', function (Blueprint $table) {
            $table->dropColumn('parent');
        });
    }
};
