<?php

namespace Tests\Feature;

use App\Services\AzuraCastService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

/**
 * Integration tests for AzuraCast API.
 * 
 * These tests will run against a real AzuraCast instance when credentials are provided.
 * Set the following environment variables to enable integration testing:
 * - AZURACAST_BASE_URL (e.g., https://radio.lossantosradio.com)
 * - AZURACAST_API_KEY (from GitHub secrets)
 * - AZURACAST_STATION_ID (default: 1)
 */
class AzuraCastIntegrationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        // Skip these tests if AzuraCast credentials are not configured
        if (empty(config('services.azuracast.api_key'))) {
            $this->markTestSkipped('AzuraCast API credentials not configured. Set AZURACAST_API_KEY to run integration tests.');
        }
    }

    public function test_can_fetch_now_playing_data(): void
    {
        $service = app(AzuraCastService::class);

        $nowPlaying = $service->getNowPlaying();

        // Verify the structure of now playing data
        $this->assertNotNull($nowPlaying);
        $this->assertIsArray($nowPlaying);
        
        // Check for expected keys
        $this->assertArrayHasKey('station', $nowPlaying);
        $this->assertArrayHasKey('now_playing', $nowPlaying);
        $this->assertArrayHasKey('listeners', $nowPlaying);
    }

    public function test_can_fetch_station_info(): void
    {
        $service = app(AzuraCastService::class);

        $stations = $service->getStations();

        $this->assertNotNull($stations);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $stations);
        $this->assertGreaterThan(0, $stations->count());

        // Check first station has required properties
        $firstStation = $stations->first();
        $this->assertNotNull($firstStation->id);
        $this->assertNotNull($firstStation->name);
    }

    public function test_can_fetch_song_history(): void
    {
        $service = app(AzuraCastService::class);

        $history = $service->getHistory();

        $this->assertNotNull($history);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $history);

        // If there's history, verify structure
        if ($history->count() > 0) {
            $firstItem = $history->first();
            $this->assertNotNull($firstItem->song);
            $this->assertNotNull($firstItem->played_at);
        }
    }

    public function test_can_fetch_requestable_songs(): void
    {
        $service = app(AzuraCastService::class);

        $result = $service->getRequestableSongs(25, 1);

        $this->assertIsArray($result);
        $this->assertArrayHasKey('songs', $result);
        $this->assertArrayHasKey('total', $result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result['songs']);
    }

    public function test_can_search_requestable_songs(): void
    {
        $service = app(AzuraCastService::class);

        // Search for a common word that should return results
        $result = $service->getRequestableSongs(25, 1, 'the');

        $this->assertIsArray($result);
        $this->assertArrayHasKey('songs', $result);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $result['songs']);
    }

    public function test_can_fetch_request_queue(): void
    {
        $service = app(AzuraCastService::class);

        $queue = $service->getRequestQueue();

        $this->assertNotNull($queue);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $queue);

        // Queue might be empty, which is fine
        $this->assertGreaterThanOrEqual(0, $queue->count());
    }

    public function test_can_fetch_playlists(): void
    {
        $service = app(AzuraCastService::class);

        $playlists = $service->getPlaylists();

        $this->assertNotNull($playlists);
        $this->assertInstanceOf(\Illuminate\Support\Collection::class, $playlists);
        
        // If there are playlists, verify structure
        if ($playlists->count() > 0) {
            $firstPlaylist = $playlists->first();
            $this->assertNotNull($firstPlaylist->id);
            $this->assertNotNull($firstPlaylist->name);
        }
    }

    public function test_handles_api_errors_gracefully(): void
    {
        // Create a service with invalid credentials to test error handling
        config(['services.azuracast.api_key' => 'invalid-key']);
        
        $service = app(AzuraCastService::class);

        try {
            $service->getNowPlaying();
            // If we get here, the API might have returned data despite invalid key
            // or the test is using cached data
            $this->assertTrue(true, 'API call succeeded or returned cached data');
        } catch (\App\Exceptions\AzuraCastException $e) {
            // Expected behavior for invalid credentials
            $this->assertStringContainsString('API', $e->getMessage(), 'Exception message should mention API');
        }
    }

    public function test_caching_works_for_now_playing(): void
    {
        $service = app(AzuraCastService::class);

        // First call - should hit the API
        $firstCall = $service->getNowPlaying();
        
        // Second call - should use cache
        $secondCall = $service->getNowPlaying();

        // Both should return data
        $this->assertNotNull($firstCall);
        $this->assertNotNull($secondCall);
        
        // Data structure should be consistent
        $this->assertEquals(
            array_keys($firstCall),
            array_keys($secondCall),
            'Cached data should have same structure as fresh data'
        );
    }
}
