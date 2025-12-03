<?php

namespace Tests\Feature;

use App\Models\SongRating;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongRatingTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_rate_a_song(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/api/ratings/', [
            'song_id' => 'test-song-123',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'created',
            ]);

        $this->assertDatabaseHas('song_ratings', [
            'song_id' => 'test-song-123',
            'user_id' => $user->id,
            'rating' => 1,
        ]);
    }

    public function test_guest_can_rate_a_song(): void
    {
        $response = $this->postJson('/api/ratings/', [
            'song_id' => 'test-song-456',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'created',
            ]);

        $this->assertDatabaseHas('song_ratings', [
            'song_id' => 'test-song-456',
            'rating' => 1,
        ]);
    }

    public function test_user_can_toggle_rating(): void
    {
        $user = User::factory()->create();

        // First rating
        $this->actingAs($user)->postJson('/api/ratings/', [
            'song_id' => 'test-song-789',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
        ]);

        // Toggle off (same rating)
        $response = $this->actingAs($user)->postJson('/api/ratings/', [
            'song_id' => 'test-song-789',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'removed',
            ]);

        $this->assertDatabaseMissing('song_ratings', [
            'song_id' => 'test-song-789',
            'user_id' => $user->id,
        ]);
    }

    public function test_user_can_change_rating(): void
    {
        $user = User::factory()->create();

        // Upvote
        $this->actingAs($user)->postJson('/api/ratings/', [
            'song_id' => 'test-song-change',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
        ]);

        // Change to downvote
        $response = $this->actingAs($user)->postJson('/api/ratings/', [
            'song_id' => 'test-song-change',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => -1,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'action' => 'updated',
            ]);

        $this->assertDatabaseHas('song_ratings', [
            'song_id' => 'test-song-change',
            'user_id' => $user->id,
            'rating' => -1,
        ]);
    }

    public function test_can_get_song_rating_counts(): void
    {
        $songId = 'test-song-counts';

        // Create some ratings
        SongRating::create([
            'song_id' => $songId,
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
            'ip_address' => '127.0.0.1',
        ]);

        SongRating::create([
            'song_id' => $songId,
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 1,
            'ip_address' => '127.0.0.2',
        ]);

        SongRating::create([
            'song_id' => $songId,
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => -1,
            'ip_address' => '127.0.0.3',
        ]);

        $response = $this->getJson("/api/ratings/song/{$songId}");

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
                'data' => [
                    'upvotes' => 2,
                    'downvotes' => 1,
                    'score' => 1,
                ],
            ]);
    }

    public function test_can_get_trending_songs(): void
    {
        // Create ratings for multiple songs
        SongRating::create([
            'song_id' => 'song-1',
            'song_title' => 'Popular Song',
            'song_artist' => 'Artist 1',
            'rating' => 1,
            'ip_address' => '127.0.0.1',
        ]);

        SongRating::create([
            'song_id' => 'song-1',
            'song_title' => 'Popular Song',
            'song_artist' => 'Artist 1',
            'rating' => 1,
            'ip_address' => '127.0.0.2',
        ]);

        SongRating::create([
            'song_id' => 'song-2',
            'song_title' => 'Another Song',
            'song_artist' => 'Artist 2',
            'rating' => 1,
            'ip_address' => '127.0.0.3',
        ]);

        $response = $this->getJson('/api/ratings/trending');

        $response->assertStatus(200)
            ->assertJson([
                'success' => true,
            ])
            ->assertJsonCount(2, 'data');
    }

    public function test_validates_rating_input(): void
    {
        $response = $this->postJson('/api/ratings/', [
            'song_id' => 'test-song',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'rating' => 5, // Invalid rating
        ]);

        $response->assertStatus(422);
    }
}
