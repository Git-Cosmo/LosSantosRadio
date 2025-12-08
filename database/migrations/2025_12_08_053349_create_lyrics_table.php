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
        Schema::create('lyrics', function (Blueprint $table) {
            $table->id();
            $table->string('song_id')->unique()->comment('AzuraCast song identifier or unique key');
            $table->string('title')->nullable();
            $table->string('artist')->nullable();
            $table->text('lyrics')->nullable();
            $table->string('source')->nullable()->comment('API source: genius, musixmatch, etc.');
            $table->string('source_url')->nullable();
            $table->boolean('is_synced')->default(false)->comment('Whether lyrics have timestamps');
            $table->json('synced_lyrics')->nullable()->comment('JSON array of time-stamped lyrics');
            $table->integer('views_count')->default(0);
            $table->timestamp('last_viewed_at')->nullable();
            $table->timestamps();

            $table->index('song_id');
            $table->index(['title', 'artist']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lyrics');
    }
};
