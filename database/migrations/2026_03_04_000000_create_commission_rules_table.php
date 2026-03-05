<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('commission_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('worker_id')->constrained('users')->onDelete('cascade');
            $table->unsignedBigInteger('package_id')->nullable()->index();
            $table->unsignedBigInteger('addon_id')->nullable()->index();
            $table->enum('commission_type', ['percentage', 'fixed'])->default('percentage');
            $table->decimal('commission_value', 10, 2)->default(0);
            $table->timestamps();

            // Prevent duplicate identical rules for same worker+package+addon
            $table->unique(['worker_id', 'package_id', 'addon_id'], 'unique_worker_package_addon');
        });
    }

    public function down()
    {
        Schema::dropIfExists('commission_rules');
    }
};
