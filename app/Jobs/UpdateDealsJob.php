<?php

namespace App\Jobs;

use App\Services\CheapSharkService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class UpdateDealsJob implements ShouldQueue
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
    public function __construct(
        public int $minSavings = 50,
        public int $maxDeals = 100
    ) {}

    /**
     * Execute the job.
     */
    public function handle(CheapSharkService $cheapShark): void
    {
        try {
            Log::info('UpdateDealsJob: Starting deals sync', [
                'min_savings' => $this->minSavings,
                'max_deals' => $this->maxDeals,
            ]);

            // Sync stores first
            $stores = $cheapShark->syncStores();
            Log::info('UpdateDealsJob: Synced stores', ['count' => $stores->count()]);

            // Sync deals
            $dealsCount = $cheapShark->syncDeals($this->minSavings, $this->maxDeals);
            Log::info('UpdateDealsJob: Synced deals', ['count' => $dealsCount]);
        } catch (\Exception $e) {
            Log::error('UpdateDealsJob: Failed to sync deals', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            throw $e;
        }
    }
}
