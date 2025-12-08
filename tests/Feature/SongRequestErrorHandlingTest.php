<?php

namespace Tests\Feature;

use App\Exceptions\AzuraCastException;
use App\Models\User;
use App\Services\AzuraCastService;
use App\Services\RequestLimitService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SongRequestErrorHandlingTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_page_handles_azuracast_service_failure(): void
    {
        // Mock AzuraCast service to throw exception
        $this->mock(AzuraCastService::class, function ($mock) {
            $mock->shouldReceive('getRequestableSongs')
                ->andThrow(new AzuraCastException('Service unavailable'));
            $mock->shouldReceive('getRequestQueue')
                ->andThrow(new AzuraCastException('Service unavailable'));
        });

        $this->mock(RequestLimitService::class, function ($mock) {
            $mock->shouldReceive('canRequest')
                ->andReturn(['allowed' => false, 'reason' => 'Service unavailable']);
        });

        $response = $this->get('/requests');

        $response->assertStatus(200);
        $response->assertSee('Unable to load song library');
        $response->assertSee('Please try again later');
    }

    public function test_song_search_api_handles_service_failure(): void
    {
        // Mock AzuraCast service to throw exception
        $this->mock(AzuraCastService::class, function ($mock) {
            $mock->shouldReceive('getRequestableSongs')
                ->andThrow(new AzuraCastException('Search failed'));
        });

        $response = $this->getJson('/requests/search?q=test');

        $response->assertStatus(503);
        $response->assertJson([
            'success' => false,
            'error' => 'Unable to search songs.',
        ]);
    }

    public function test_song_request_submission_handles_rate_limiting(): void
    {
        // Mock rate limiter to deny request
        $this->mock(RequestLimitService::class, function ($mock) {
            $mock->shouldReceive('canRequest')
                ->andReturn([
                    'allowed' => false,
                    'reason' => 'You have reached your request limit. Please try again later.',
                ]);
        });

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/requests', [
            'song_id' => 'test-song-id',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
        ]);

        $response->assertStatus(429);
        $response->assertJson([
            'success' => false,
            'error' => 'You have reached your request limit. Please try again later.',
        ]);
    }

    public function test_song_request_submission_handles_azuracast_failure(): void
    {
        // Mock rate limiter to allow request
        $this->mock(RequestLimitService::class, function ($mock) {
            $mock->shouldReceive('canRequest')
                ->andReturn([
                    'allowed' => true,
                    'remaining' => 5,
                ]);
        });

        // Mock AzuraCast service to throw exception
        $this->mock(AzuraCastService::class, function ($mock) {
            $mock->shouldReceive('submitRequest')
                ->andThrow(new AzuraCastException('Request failed'));
        });

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/requests', [
            'song_id' => 'test-song-id',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
        ]);

        $response->assertStatus(503);
        $response->assertJson([
            'success' => false,
            'error' => 'Unable to submit request. Please try again.',
        ]);
    }

    public function test_song_request_submission_handles_azuracast_rejection(): void
    {
        // Mock rate limiter to allow request
        $this->mock(RequestLimitService::class, function ($mock) {
            $mock->shouldReceive('canRequest')
                ->andReturn([
                    'allowed' => true,
                    'remaining' => 5,
                ]);
        });

        // Mock AzuraCast service to return failure
        $this->mock(AzuraCastService::class, function ($mock) {
            $mock->shouldReceive('submitRequest')
                ->andReturn([
                    'success' => false,
                    'message' => 'This song is not available for requests.',
                ]);
        });

        $user = User::factory()->create();

        $response = $this->actingAs($user)->postJson('/requests', [
            'song_id' => 'test-song-id',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
        ]);

        $response->assertStatus(400);
        $response->assertJson([
            'success' => false,
            'error' => 'This song is not available for requests.',
        ]);
    }

    public function test_request_queue_api_handles_service_failure(): void
    {
        // Mock AzuraCast service to throw exception
        $this->mock(AzuraCastService::class, function ($mock) {
            $mock->shouldReceive('getRequestQueue')
                ->andThrow(new AzuraCastException('Queue unavailable'));
        });

        $response = $this->getJson('/requests/queue');

        $response->assertStatus(503);
        $response->assertJson([
            'success' => false,
            'error' => 'Unable to fetch request queue.',
        ]);
    }

    public function test_request_validation_requires_all_fields(): void
    {
        $user = User::factory()->create();

        // Missing song_artist
        $response = $this->actingAs($user)->postJson('/requests', [
            'song_id' => 'test-song-id',
            'song_title' => 'Test Song',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['song_artist']);
    }

    public function test_guest_email_validation(): void
    {
        // Invalid email format
        $response = $this->postJson('/requests', [
            'song_id' => 'test-song-id',
            'song_title' => 'Test Song',
            'song_artist' => 'Test Artist',
            'guest_email' => 'invalid-email',
        ]);

        $response->assertStatus(422);
        $response->assertJsonValidationErrors(['guest_email']);
    }
}
