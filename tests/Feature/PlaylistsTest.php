<?php

namespace Tests\Feature;

use Tests\TestCase;

class PlaylistsTest extends TestCase
{
    public function test_playlists_api_returns_json(): void
    {
        $response = $this->getJson('/api/playlists/');

        // The API may return an error if AzuraCast is not configured,
        // but should return valid JSON
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'success',
        ]);
    }

    public function test_active_playlists_api_returns_json(): void
    {
        $response = $this->getJson('/api/playlists/active');

        // The API may return an error if AzuraCast is not configured,
        // but should return valid JSON
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'success',
        ]);
    }

    public function test_current_playlists_api_returns_json(): void
    {
        $response = $this->getJson('/api/playlists/current');

        // The API may return an error if AzuraCast is not configured,
        // but should return valid JSON
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'success',
        ]);
    }
}
