<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('visitor_actions', function (Blueprint $table) {
            $table->text('uri')->change();
            $table->text('user_agent')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('visitor_actions', function (Blueprint $table) {
            $table->string('uri', 255)->change();
            $table->string('user_agent', 255)->nullable()->change();
        });
    }
};
