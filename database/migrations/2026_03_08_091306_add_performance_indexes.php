<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Speeds up homepage query: WHERE status='active' ORDER BY order_by
        Schema::table('packages', function (Blueprint $table) {
            $table->index(['status', 'order_by'], 'packages_status_order_by_index');
            $table->index(['organizer_id', 'status'], 'packages_organizer_id_status_index');
        });

        // Speeds up organizer profile lookup: WHERE slug=? AND is_active AND visibility AND type
        Schema::table('organizers', function (Blueprint $table) {
            $table->index(['slug', 'is_active', 'visibility', 'type'], 'organizers_slug_active_visibility_index');
        });
    }

    public function down(): void
    {
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex('packages_status_order_by_index');
            $table->dropIndex('packages_organizer_id_status_index');
        });

        Schema::table('organizers', function (Blueprint $table) {
            $table->dropIndex('organizers_slug_active_visibility_index');
        });
    }
};
