<?php

namespace App\Jobs;

use App\Services\RedditScraperService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class SyncFreeGamesJob implements ShouldQueue
{
    use Queueable;

    /**
     * The number of times the job may be attempted.
     */
    public int $tries = 3;

    /**
     * The number of seconds to wait before retrying the job.
     */
    public int $backoff = 60;

    /**
     * Create a new job instance.
     */
    public function __construct() {}

    /**
     * Execute the job.
     */
    public function handle(RedditScraperService $reddit): void
    {
        try {
            Log::info('SyncFreeGamesJob: Starting free games sync');

            $count = $reddit->syncFreeGames();

            Log::info('SyncFreeGamesJob: Synced free games', ['count' => $count]);
        } catch (\Exception $e) {
            Log::error('SyncFreeGamesJob: Failed to sync free games', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}

