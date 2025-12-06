<?php

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
