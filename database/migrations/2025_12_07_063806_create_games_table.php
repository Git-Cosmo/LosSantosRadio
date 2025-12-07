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
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('igdb_id')->unique()->nullable();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->text('storyline')->nullable();
            $table->string('cover_image')->nullable();
            $table->json('screenshots')->nullable();
            $table->json('genres')->nullable();
            $table->json('platforms')->nullable();
            $table->json('websites')->nullable();
            $table->decimal('rating', 5, 2)->nullable();
            $table->integer('rating_count')->nullable();
            $table->decimal('aggregated_rating', 5, 2)->nullable();
            $table->integer('aggregated_rating_count')->nullable();
            $table->timestamp('release_date')->nullable();
            $table->string('igdb_url')->nullable();
            $table->timestamps();

            $table->index('igdb_id');
            $table->index('slug');
            $table->index('release_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('games');
    }
};
