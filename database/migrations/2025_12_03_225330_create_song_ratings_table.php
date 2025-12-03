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
        Schema::create('song_ratings', function (Blueprint $table) {
            $table->id();
            $table->string('song_id'); // AzuraCast song ID
            $table->string('song_title');
            $table->string('song_artist');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address', 45)->nullable(); // For guest rating tracking
            $table->tinyInteger('rating'); // 1 for upvote, -1 for downvote
            $table->timestamps();

            // Prevent duplicate ratings from same user/IP for same song
            $table->unique(['song_id', 'user_id'], 'song_ratings_user_unique');
            $table->index(['song_id', 'ip_address'], 'song_ratings_ip_index');
            $table->index('rating');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_ratings');
    }
};
