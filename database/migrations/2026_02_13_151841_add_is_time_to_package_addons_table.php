<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('package_addons', function (Blueprint $table) {
            $table->boolean('is_time')
                  ->default(false)
                  ->after('is_required');
        });
    }

    public function down(): void
    {
        Schema::table('package_addons', function (Blueprint $table) {
            $table->dropColumn('is_time');
        });
    }
};
