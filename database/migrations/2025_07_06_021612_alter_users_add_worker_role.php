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
        DB::statement("ALTER TABLE users MODIFY role ENUM('organizer','marshall','participant','superadmin','worker') DEFAULT 'participant'");
    }

    public function down(): void
    {
        // Revert if needed
        DB::statement("ALTER TABLE users MODIFY role ENUM('organizer','marshall','participant','superadmin') DEFAULT 'participant'");
    }
};
