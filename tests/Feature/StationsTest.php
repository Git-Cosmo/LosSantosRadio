<?php

namespace Tests\Feature;

use Tests\TestCase;

class StationsTest extends TestCase
{
    public function test_stations_page_loads(): void
    {
        $response = $this->get('/stations');

        // The page may show an error if AzuraCast is not configured,
        // but it should still render successfully
        $response->assertStatus(200);
        $response->assertSee('Radio Stations');
    }

    public function test_stations_api_returns_json(): void
    {
        $response = $this->getJson('/api/stations/');

        // The API may return an error if AzuraCast is not configured,
        // but should return valid JSON
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'success',
        ]);
    }

    public function test_stations_now_playing_api_returns_json(): void
    {
        $response = $this->getJson('/api/stations/now-playing');

        // The API may return an error if AzuraCast is not configured,
        // but should return valid JSON
        $response->assertHeader('Content-Type', 'application/json');
        $response->assertJsonStructure([
            'success',
        ]);
    }
}
