<?php

namespace App\Console\Commands;

use App\Models\RssFeed;
use App\Services\RssFeedService;
use Illuminate\Console\Command;

class ImportRssFeeds extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'rss:import {--feed= : Import a specific feed by ID}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Import articles from RSS feeds';

    public function __construct(
        protected RssFeedService $rssFeedService
    ) {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $feedId = $this->option('feed');

        if ($feedId) {
            return $this->importSingleFeed($feedId);
        }

        return $this->importAllFeeds();
    }

    /**
     * Import a single RSS feed.
     */
    protected function importSingleFeed(int $feedId): int
    {
        $feed = RssFeed::find($feedId);

        if (! $feed) {
            $this->error("RSS feed with ID {$feedId} not found.");

            return self::FAILURE;
        }

        $this->info("Importing from: {$feed->name}");

        $result = $this->rssFeedService->importFromUrl($feed->url);

        if ($result['imported'] > 0) {
            $feed->markAsFetched($result['imported']);
            $this->info("✓ Imported {$result['imported']} articles from {$feed->name}");
        } else {
            $this->warn("✗ No articles imported from {$feed->name}");
        }

        if (! empty($result['errors'])) {
            foreach ($result['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Import all active RSS feeds.
     */
    protected function importAllFeeds(): int
    {
        $feeds = RssFeed::where('is_active', true)->get()
            ->filter(fn ($feed) => $feed->isDueForFetch());

        if ($feeds->isEmpty()) {
            $this->info('No RSS feeds are due for fetching.');

            return self::SUCCESS;
        }

        $this->info("Found {$feeds->count()} feeds to import.");

        $totalImported = 0;

        foreach ($feeds as $feed) {
            $this->line("Importing from: {$feed->name}...");

            $result = $this->rssFeedService->importFromUrl($feed->url);

            if ($result['imported'] > 0) {
                $feed->markAsFetched($result['imported']);
                $this->info("  ✓ Imported {$result['imported']} articles");
                $totalImported += $result['imported'];
            } else {
                $this->warn('  ✗ No new articles');
            }

            if (! empty($result['errors'])) {
                foreach ($result['errors'] as $error) {
                    $this->error("  - {$error}");
                }
            }
        }

        $this->info("Total articles imported: {$totalImported}");

        return self::SUCCESS;
    }
}
