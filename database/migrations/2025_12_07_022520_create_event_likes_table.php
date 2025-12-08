<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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

        // Only attempt to create filtered index on databases that support it
        if (in_array($driver, ['pgsql', 'sqlite'])) {
            try {
                DB::statement('CREATE UNIQUE INDEX event_likes_event_ip_null_user_unique ON event_likes(event_id, ip_address) WHERE user_id IS NULL');
            } catch (\Illuminate\Database\QueryException $e) {
                // Fallback: catch any syntax errors in case database version doesn't support filtered indexes
                $errorMessage = strtolower($e->getMessage());

                if (str_contains($errorMessage, 'syntax error') || str_contains($errorMessage, "near 'where'")) {
                    Log::info("Skipped filtered index creation for event_likes table - not supported on this {$driver} version");
                } else {
                    // Unexpected error, re-throw to prevent silent failures
                    throw $e;
                }
            }
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
