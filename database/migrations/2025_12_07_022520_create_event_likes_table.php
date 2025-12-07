<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('event_likes', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('ip_address')->nullable();
            $table->timestamps();

            // Unique constraint for authenticated users
            $table->unique(['event_id', 'user_id'], 'event_likes_event_user_unique');

            // Index for IP-based queries
            $table->index(['event_id', 'ip_address']);
        });

        // Add a partial unique index for guest likes (WHERE user_id IS NULL)
        // This prevents duplicate guest likes from same IP without blocking authenticated users
        // Only supported in PostgreSQL and SQLite 3.8.0+
        $driver = DB::connection()->getDriverName();
        
        if (in_array($driver, ['pgsql', 'sqlite'])) {
            DB::statement('CREATE UNIQUE INDEX event_likes_event_ip_null_user_unique ON event_likes(event_id, ip_address) WHERE user_id IS NULL');
        }
        // Note: For MySQL/MariaDB, uniqueness for guest likes must be enforced at application level
        // as they don't support filtered/partial indexes with WHERE clauses
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Schema::dropIfExists will automatically drop all indexes and constraints
        Schema::dropIfExists('event_likes');
    }
};
