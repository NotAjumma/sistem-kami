<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->unsignedTinyInteger('reminder_quiet_start')->default(0)->after('fonnte_token');
            $table->unsignedTinyInteger('reminder_quiet_end')->default(6)->after('reminder_quiet_start');
        });
    }

    public function down(): void
    {
        Schema::table('organizers', function (Blueprint $table) {
            $table->dropColumn(['reminder_quiet_start', 'reminder_quiet_end']);
        });
    }
};
