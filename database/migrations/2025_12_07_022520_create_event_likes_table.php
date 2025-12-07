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
        // For MySQL/MariaDB/SQL Server, uniqueness must be enforced at application level
        $driver = DB::connection()->getDriverName();
        
        try {
            DB::statement('CREATE UNIQUE INDEX event_likes_event_ip_null_user_unique ON event_likes(event_id, ip_address) WHERE user_id IS NULL');
        } catch (\Illuminate\Database\QueryException $e) {
            // Silently skip if database doesn't support filtered/partial indexes (MySQL/MariaDB/SQL Server)
            // This is expected for MySQL/MariaDB which don't support WHERE clauses in indexes
            \Log::info("Skipped filtered index creation for event_likes table - not supported on {$driver} database");
        }
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
