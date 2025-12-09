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
        Schema::create('media_subcategories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('media_category_id')->constrained()->cascadeOnDelete();
            $table->string('name'); // e.g., Maps, Skins, Plugins, Mods, Textures
            $table->string('slug');
            $table->text('description')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            
            $table->unique(['media_category_id', 'slug']);
            $table->index(['media_category_id', 'is_active', 'order']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('media_subcategories');
    }
};
