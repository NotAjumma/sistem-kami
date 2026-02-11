<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('visitor_actions', function (Blueprint $table) {
            $table->id();

            // identify visitor
            $table->uuid('visitor_id');

            // what user did
            $table->string('action'); // view_package, check_slot, whatsapp_click

            // page name (optional label)
            $table->string('page')->nullable();

            // actual URL visited
            $table->string('uri')->nullable();

            // package id / slot id etc
            $table->unsignedBigInteger('reference_id')->nullable();

            // tracking info
            $table->ipAddress('ip_address')->nullable();
            $table->string('browser')->nullable();
            $table->string('platform')->nullable();
            $table->string('device')->nullable();
            $table->text('user_agent')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('visitor_actions');
    }
};
