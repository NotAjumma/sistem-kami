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
        Schema::table('packages', function (Blueprint $table) {
            $table->decimal('service_charge_fixed', 10, 2)->default(1.00)->after('discount_percentage');
            $table->decimal('service_charge_percentage', 5, 2)->nullable()->after('service_charge_fixed');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropColumn(['service_charge_fixed', 'service_charge_percentage']);
        });
    }
};
