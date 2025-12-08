<?php

use App\Jobs\SyncFreeGamesJob;
use App\Jobs\UpdateDealsJob;
use App\Jobs\UpdateIGDBJob;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote');

// Generate sitemap every 6 hours
// NOTE: Laravel's scheduler requires a cron job running `php artisan schedule:run` every minute.
// To verify the scheduler is working, run: `php artisan schedule:test`
// For development, you can use: `php artisan schedule:work`
Schedule::command('sitemap:generate')->everySixHours();

// Update game deals from CheapShark every 4 hours
// Parameters: minSavings=50 (only deals with 50%+ off), maxDeals=100 (up to 100 deals per sync)
Schedule::job(new UpdateDealsJob(50, 100))->everyFourHours()->withoutOverlapping();

// Sync free games from Reddit every 6 hours
Schedule::job(new SyncFreeGamesJob)->everySixHours()->withoutOverlapping();

// Update IGDB game metadata once daily
Schedule::job(new UpdateIGDBJob)->daily()->withoutOverlapping();
