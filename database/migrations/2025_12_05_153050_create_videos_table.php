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
        Schema::create('videos', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('video_url');
            $table->string('embed_url')->nullable();
            $table->string('thumbnail_url')->nullable();
            $table->enum('category', ['ylyl', 'clips'])->default('ylyl');
            $table->enum('platform', ['youtube', 'twitch', 'kick', 'reddit', 'other'])->default('reddit');
            $table->string('source')->default('reddit');
            $table->string('source_id')->nullable();
            $table->string('author')->nullable();
            $table->integer('upvotes')->default(0);
            $table->integer('views')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamp('posted_at')->nullable();
            $table->timestamps();

            $table->index(['category', 'is_active']);
            $table->index(['platform', 'is_active']);
            $table->index('posted_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('videos');
    }
};
