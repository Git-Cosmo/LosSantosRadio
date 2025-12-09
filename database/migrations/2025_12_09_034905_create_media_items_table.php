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
        Schema::create('media_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_category_id')->constrained()->cascadeOnDelete();
            $table->foreignId('media_subcategory_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('content')->nullable(); // detailed information
            $table->string('version', 50)->nullable();
            $table->string('file_size', 50)->nullable(); // e.g., "15.5 MB"
            $table->integer('downloads_count')->default(0);
            $table->integer('views_count')->default(0);
            $table->decimal('rating', 3, 2)->default(0.00); // 0.00 to 5.00
            $table->integer('ratings_count')->default(0);
            $table->boolean('is_featured')->default(false);
            $table->boolean('is_approved')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamp('published_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
            
            // Indexes for efficient querying
            $table->index(['media_category_id', 'media_subcategory_id', 'is_active'], 'media_items_cat_subcat_active_idx');
            $table->index(['is_featured', 'is_approved', 'is_active'], 'media_items_featured_approved_active_idx');
            $table->index('downloads_count');
            $table->index('views_count');
            $table->index('rating');
            $table->index('published_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_items');
    }
};
