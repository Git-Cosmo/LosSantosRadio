<?php

namespace Tests\Feature;

use App\Livewire\Leaderboard;
use App\Models\SongRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class LeaderboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_leaderboard_component_renders(): void
    {
        Livewire::test(Leaderboard::class)
            ->assertStatus(200);
    }

    public function test_leaderboard_shows_top_requesters(): void
    {
        $user1 = User::factory()->create(['name' => 'Top Requester']);
        $user2 = User::factory()->create(['name' => 'Second Place']);

        // Create more requests for user1
        SongRequest::factory()->count(5)->create(['user_id' => $user1->id]);
        SongRequest::factory()->count(3)->create(['user_id' => $user2->id]);

        Livewire::test(Leaderboard::class)
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

        Livewire::test(Leaderboard::class)
            ->call('setTimeframe', 'week')
            ->assertSet('timeframe', 'week');
    }

    public function test_leaderboard_excludes_rejected_requests(): void
    {
        $user = User::factory()->create(['name' => 'Test User']);

        SongRequest::factory()->create([
            'user_id' => $user->id,
            'status' => SongRequest::STATUS_REJECTED,
        ]);

        // Should show empty state since the only request was rejected
        Livewire::test(Leaderboard::class)
            ->assertSee('No requests yet');
    }
}
