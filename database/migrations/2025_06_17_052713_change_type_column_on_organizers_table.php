<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::table('organizers', function (Blueprint $table) {
            // Change ENUM to string (VARCHAR)
            $table->string('type')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('organizers', function (Blueprint $table) {
            // Revert to original ENUM definition
            $table->enum('type', ['individual', 'company', 'government', 'non-profit'])
                ->default('company')
                ->nullable()
                ->change();
        });
    }
};
