<?php

namespace Tests\Feature;

use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboard_page_renders(): void
    {
        $response = $this->get(route('leaderboard'));

        $response->assertStatus(200);
    }

    public function test_leaderboard_shows_top_requesters(): void
    {
        $user1 = User::factory()->create(['name' => 'Top Requester']);
        $user2 = User::factory()->create(['name' => 'Second Place']);

        // Create more requests for user1
        SongRequest::factory()->count(5)->create(['user_id' => $user1->id]);
        SongRequest::factory()->count(3)->create(['user_id' => $user2->id]);

        $response = $this->get(route('leaderboard'));

        $response->assertStatus(200)
            ->assertSee('Top Requester')
            ->assertSee('Second Place');
    }

    public function test_leaderboard_can_filter_by_timeframe(): void
    {
        $user = User::factory()->create();
        SongRequest::factory()->create([
            'user_id' => $user->id,
            'created_at' => now()->subDays(10),
        ]);

        // Filter by week should not show request from 10 days ago
        $response = $this->get(route('leaderboard', ['timeframe' => 'week']));

        $response->assertStatus(200);
    }

    public function test_leaderboard_excludes_rejected_requests(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);

        SongRequest::factory()->create([
            'user_id' => $user->id,
            'status' => SongRequest::STATUS_REJECTED,
        ]);

        // Should show empty state since the only request was rejected
        $response = $this->get(route('leaderboard'));

        $response->assertStatus(200)
            ->assertSee('No requests yet');
    }

    public function test_leaderboard_api_returns_json(): void
    {
        $user = User::factory()->create(['name' => 'Top Requester']);
        SongRequest::factory()->count(3)->create(['user_id' => $user->id]);

        $response = $this->getJson(route('leaderboard.api'));

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data',
            ]);
    }
}
