<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Tests\TestCase;

class SeedersTest extends TestCase
{
    use RefreshDatabase;

    public function test_event_seeder_creates_2026_gaming_events(): void
    {
        // Run the EventSeeder
        Artisan::call('db:seed', ['--class' => 'EventSeeder']);

        // Check that events were created
        $this->assertDatabaseHas('events', [
            'title' => 'E3 2026',
            'event_type' => 'expo',
            'is_published' => true,
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'Gamescom 2026',
            'event_type' => 'expo',
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'The International 2026',
            'event_type' => 'tournament',
        ]);

        $this->assertDatabaseHas('events', [
            'title' => 'League of Legends World Championship 2026',
            'event_type' => 'tournament',
        ]);

        // Verify we have at least 18 events (based on EventSeeder implementation)
        // Using >= to allow for additional events that may be added in the future
        $this->assertGreaterThanOrEqual(18, \App\Models\Event::count());
    }

    public function test_poll_seeder_creates_gaming_polls(): void
    {
        // Run the PollSeeder
        Artisan::call('db:seed', ['--class' => 'PollSeeder']);

        // Check that polls were created
        $this->assertDatabaseHas('polls', [
            'question' => 'What\'s your favorite gaming platform?',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('polls', [
            'question' => 'Which game genre do you play the most?',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('polls', [
            'question' => 'Best gaming soundtrack of all time?',
            'is_active' => true,
        ]);

        // Verify we have at least 6 polls (based on PollSeeder implementation)
        // Using >= to allow for additional polls that may be added in the future
        $this->assertGreaterThanOrEqual(6, \App\Models\Poll::count());
    }

    public function test_poll_seeder_creates_poll_options(): void
    {
        // Run the PollSeeder
        Artisan::call('db:seed', ['--class' => 'PollSeeder']);

        // Check that poll options were created
        $this->assertDatabaseHas('poll_options', [
            'option_text' => 'PC Master Race',
        ]);

        $this->assertDatabaseHas('poll_options', [
            'option_text' => 'PlayStation',
        ]);

        // Verify we have many poll options
        $this->assertGreaterThan(20, \App\Models\PollOption::count());
    }

    public function test_achievement_seeder_creates_achievements(): void
    {
        // Run the AchievementSeeder
        Artisan::call('db:seed', ['--class' => 'AchievementSeeder']);

        // Check that achievements were created for different categories
        $this->assertDatabaseHas('achievements', [
            'name' => '3 Day Streak',
            'category' => 'streaks',
        ]);

        $this->assertDatabaseHas('achievements', [
            'name' => 'First Request',
            'category' => 'requests',
        ]);

        $this->assertDatabaseHas('achievements', [
            'name' => 'Level 5',
            'category' => 'levels',
        ]);

        // Verify we have multiple achievements
        $this->assertGreaterThan(10, \App\Models\Achievement::count());
    }

    public function test_rss_feed_seeder_creates_feeds(): void
    {
        // Run the RssFeedSeeder
        Artisan::call('db:seed', ['--class' => 'RssFeedSeeder']);

        // Check that RSS feeds were created
        $this->assertDatabaseHas('rss_feeds', [
            'name' => 'IGN All News',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('rss_feeds', [
            'name' => 'GameSpot Latest News',
            'is_active' => true,
        ]);

        $this->assertDatabaseHas('rss_feeds', [
            'name' => 'Polygon',
            'is_active' => true,
        ]);

        // Verify we have multiple feeds
        $this->assertGreaterThan(10, \App\Models\RssFeed::count());
    }
}
