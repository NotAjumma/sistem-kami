<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
     public function up(): void
    {
        Schema::table('visitor_actions', function (Blueprint $table) {
            $table->index(['action', 'page', 'reference_id', 'created_at'], 'idx_profile_visits');
            $table->index(['action', 'reference_id', 'created_at'], 'idx_package_views');
            $table->index(['action', 'reference_id', 'created_at'], 'idx_whatsapp_clicks');
        });
    }

    public function down(): void
    {
        Schema::table('visitor_actions', function (Blueprint $table) {
            $table->dropIndex('idx_profile_visits');
            $table->dropIndex('idx_package_views');
            $table->dropIndex('idx_whatsapp_clicks');
        });
    }
};
