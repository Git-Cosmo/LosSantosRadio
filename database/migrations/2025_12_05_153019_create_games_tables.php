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
        // Game stores (from CheapShark)
        Schema::create('game_stores', function (Blueprint $table) {
            $table->id();
            $table->string('external_id')->unique();
            $table->string('name');
            $table->boolean('is_active')->default(true);
            $table->json('images')->nullable();
            $table->timestamps();
        });

        // Free games (from Reddit sources)
        Schema::create('free_games', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('description')->nullable();
            $table->string('platform')->nullable();
            $table->string('store')->nullable();
            $table->string('url');
            $table->string('image_url')->nullable();
            $table->timestamp('expires_at')->nullable();
            $table->string('source')->default('reddit');
            $table->string('source_id')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['is_active', 'expires_at']);
        });

        // Game deals (from CheapShark API)
        Schema::create('game_deals', function (Blueprint $table) {
            $table->id();
            $table->string('deal_id')->unique();
            $table->string('title');
            $table->string('slug');
            $table->decimal('sale_price', 10, 2);
            $table->decimal('normal_price', 10, 2);
            $table->integer('savings_percent')->default(0);
            $table->decimal('metacritic_score', 5, 2)->nullable();
            $table->string('thumb')->nullable();
            $table->foreignId('store_id')->nullable()->constrained('game_stores')->nullOnDelete();
            $table->string('external_game_id')->nullable();
            $table->boolean('is_on_sale')->default(true);
            $table->timestamp('deal_rating')->nullable();
            $table->timestamps();

            $table->index(['is_on_sale', 'savings_percent']);
            $table->index('store_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_deals');
        Schema::dropIfExists('free_games');
        Schema::dropIfExists('game_stores');
    }
};
