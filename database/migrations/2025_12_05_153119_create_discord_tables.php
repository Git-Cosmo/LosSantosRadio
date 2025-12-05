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
        // Discord roles synced from Discord server
        Schema::create('discord_roles', function (Blueprint $table) {
            $table->id();
            $table->string('discord_id')->unique();
            $table->string('name');
            $table->string('color')->nullable();
            $table->integer('position')->default(0);
            $table->json('permissions')->nullable();
            $table->boolean('is_synced')->default(true);
            $table->timestamps();
        });

        // Discord users synced from Discord server
        Schema::create('discord_members', function (Blueprint $table) {
            $table->id();
            $table->string('discord_id')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('username');
            $table->string('discriminator')->nullable();
            $table->string('avatar')->nullable();
            $table->json('role_ids')->nullable();
            $table->timestamp('joined_at')->nullable();
            $table->boolean('is_synced')->default(true);
            $table->timestamps();

            $table->index('user_id');
        });

        // Discord bot logs
        Schema::create('discord_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('type', ['info', 'warning', 'error', 'sync', 'command']);
            $table->string('action');
            $table->text('message');
            $table->json('data')->nullable();
            $table->timestamps();

            $table->index(['type', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discord_logs');
        Schema::dropIfExists('discord_members');
        Schema::dropIfExists('discord_roles');
    }
};
