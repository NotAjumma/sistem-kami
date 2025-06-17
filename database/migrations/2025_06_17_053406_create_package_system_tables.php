<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {

        // Package Categories (Optional)
        Schema::create('package_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Packages
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained('package_categories')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->decimal('base_price', 10, 2);
            $table->decimal('final_price', 10, 2)->nullable();
            $table->unsignedTinyInteger('discount_percentage')->nullable();
            $table->enum('status', ['active', 'inactive', 'draft'])->default('inactive');
            $table->timestamp('valid_from')->nullable();
            $table->timestamp('valid_until')->nullable();
            $table->timestamps();
        });

        // Package Items
        Schema::create('package_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('title');
            $table->text('description')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('unit_price', 10, 2)->nullable();
            $table->boolean('is_optional')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Package Addons
        Schema::create('package_addons', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('price', 10, 2);
            $table->boolean('is_required')->default(false);
            $table->timestamps();
        });

        // Package Images
        Schema::create('package_images', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->string('url');
            $table->string('alt_text')->nullable();
            $table->boolean('is_cover')->default(false);
            $table->unsignedSmallInteger('sort_order')->default(0);
            $table->timestamps();
        });

        // Package Discounts (Optional)
        Schema::create('package_discounts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->enum('type', ['percentage', 'fixed']);
            $table->decimal('amount', 10, 2);
            $table->timestamp('starts_at')->nullable();
            $table->timestamp('ends_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('package_discounts');
        Schema::dropIfExists('package_images');
        Schema::dropIfExists('package_addons');
        Schema::dropIfExists('package_items');
        Schema::dropIfExists('packages');
        Schema::dropIfExists('package_categories');
    }
};
