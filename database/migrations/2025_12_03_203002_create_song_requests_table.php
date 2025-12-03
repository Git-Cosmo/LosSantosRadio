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
        Schema::create('song_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('song_id'); // AzuraCast song ID
            $table->string('song_title');
            $table->string('song_artist');
            $table->string('ip_address', 45)->nullable();
            $table->string('session_id')->nullable();
            $table->string('guest_email')->nullable();
            $table->string('status')->default('pending'); // pending, playing, played, rejected, cancelled
            $table->string('azuracast_request_id')->nullable();
            $table->timestamp('played_at')->nullable();
            $table->timestamps();

            $table->index(['user_id', 'created_at']);
            $table->index(['ip_address', 'created_at']);
            $table->index(['session_id', 'created_at']);
            $table->index(['status', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('song_requests');
    }
};
