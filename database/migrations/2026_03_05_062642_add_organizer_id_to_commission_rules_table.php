<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('commission_rules', function (Blueprint $table) {
            $table->unsignedBigInteger('organizer_id')->after('id');
            $table->index('organizer_id');
        });
    }

    public function down()
    {
        Schema::table('commission_rules', function (Blueprint $table) {
            $table->dropIndex(['organizer_id']);
            $table->dropColumn('organizer_id');
        });
    }
};
