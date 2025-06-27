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
        // Rename column category_id to package_category_id
        Schema::table('vendor_time_slot_limits', function (Blueprint $table) {
            $table->renameColumn('category_id', 'package_category_id');
        });

        // Modify vendor_time_slots table
        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->dropColumn('date'); // remove 'date' column
            $table->foreignId('package_id')->nullable()->after('organizer_id')->constrained('packages')->onDelete('cascade');
        });
    }

    public function down()
    {
        // Revert changes
        Schema::table('vendor_time_slot_limits', function (Blueprint $table) {
            $table->renameColumn('package_category_id', 'category_id');
        });

        Schema::table('vendor_time_slots', function (Blueprint $table) {
            $table->date('date')->after('organizer_id');
            $table->dropConstrainedForeignId('package_id');
        });
    }
};
