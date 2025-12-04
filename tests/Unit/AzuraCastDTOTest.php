<?php

namespace Tests\Unit;

use App\DTOs\NowPlayingDTO;
use App\DTOs\PlaylistDTO;
use App\DTOs\SongDTO;
use App\DTOs\SongHistoryDTO;
use App\DTOs\StationDTO;
use PHPUnit\Framework\TestCase;

class AzuraCastDTOTest extends TestCase
{
    public function test_song_dto_maps_api_response(): void
    {
        $apiData = [
            'id' => '9f33bbc912c19603e51be8e0987d076b',
            'title' => 'Test Song',
            'artist' => 'Test Artist',
            'album' => 'Test Album',
            'art' => 'https://example.com/art.jpg',
            'lyrics' => 'La la la',
            'genre' => 'Rock',
            'isrc' => 'US28E1600021',
            'duration' => 180,
        ];

        $song = SongDTO::fromApi($apiData);

        $this->assertEquals('9f33bbc912c19603e51be8e0987d076b', $song->id);
        $this->assertEquals('Test Song', $song->title);
        $this->assertEquals('Test Artist', $song->artist);
        $this->assertEquals('Test Album', $song->album);
        $this->assertEquals('https://example.com/art.jpg', $song->art);
        $this->assertEquals('La la la', $song->lyrics);
        $this->assertEquals('Rock', $song->genre);
        $this->assertEquals('US28E1600021', $song->isrc);
        $this->assertEquals(180, $song->duration);
    }

    public function test_song_dto_handles_text_fallback(): void
    {
        $apiData = [
            'id' => 'test123',
            'text' => 'Artist - Title Combined',
            'artist' => 'Test Artist',
        ];

        $song = SongDTO::fromApi($apiData);

        $this->assertEquals('Artist - Title Combined', $song->title);
    }

    public function test_song_dto_handles_song_id_fallback(): void
    {
        $apiData = [
            'song_id' => 'fallback123',
            'title' => 'Test',
            'artist' => 'Test',
        ];

        $song = SongDTO::fromApi($apiData);

        $this->assertEquals('fallback123', $song->id);
    }

    public function test_station_dto_maps_api_response(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Los Santos Radio',
            'shortcode' => 'lsradio',
            'description' => 'The best radio station',
            'url' => 'https://example.com',
            'listen_url' => 'https://stream.example.com/radio.mp3',
            'public_player_url' => 'https://example.com/public/lsradio',
            'is_online' => true,
            'requests_enabled' => true,
            'request_delay' => 30,
            'request_threshold' => 15,
        ];

        $station = StationDTO::fromApi($apiData);

        $this->assertEquals(1, $station->id);
        $this->assertEquals('Los Santos Radio', $station->name);
        $this->assertEquals('lsradio', $station->shortcode);
        $this->assertEquals('The best radio station', $station->description);
        $this->assertEquals('https://example.com', $station->url);
        $this->assertEquals('https://stream.example.com/radio.mp3', $station->listenUrl);
        $this->assertEquals('https://example.com/public/lsradio', $station->publicPlaylistUri);
        $this->assertTrue($station->isOnline);
        $this->assertTrue($station->enableRequests);
        $this->assertEquals(30, $station->requestDelay);
        $this->assertEquals(15, $station->requestThreshold);
    }

    public function test_station_dto_handles_legacy_enable_requests(): void
    {
        // Test with legacy 'enable_requests' field
        $apiData = [
            'id' => 1,
            'name' => 'Test Station',
            'shortcode' => 'test',
            'enable_requests' => true,
        ];

        $station = StationDTO::fromApi($apiData);

        $this->assertTrue($station->enableRequests);
    }

    public function test_station_dto_prefers_requests_enabled_over_legacy(): void
    {
        // When both fields are present, prefer 'requests_enabled'
        $apiData = [
            'id' => 1,
            'name' => 'Test Station',
            'shortcode' => 'test',
            'requests_enabled' => false,
            'enable_requests' => true, // Legacy field, should be ignored
        ];

        $station = StationDTO::fromApi($apiData);

        $this->assertFalse($station->enableRequests);
    }

    public function test_song_history_dto_maps_api_response(): void
    {
        $apiData = [
            'sh_id' => 12345,
            'played_at' => 1609480800,
            'duration' => 240,
            'playlist' => 'Top Hits',
            'streamer' => 'DJ Test',
            'is_request' => true,
            'song' => [
                'id' => 'abc123',
                'title' => 'History Song',
                'artist' => 'History Artist',
            ],
        ];

        $history = SongHistoryDTO::fromApi($apiData);

        $this->assertEquals(12345, $history->id);
        $this->assertEquals(240, $history->duration);
        $this->assertEquals('Top Hits', $history->playlist);
        $this->assertEquals('DJ Test', $history->dj);
        $this->assertTrue($history->isRequest);
        $this->assertEquals('abc123', $history->song->id);
        $this->assertEquals('History Song', $history->song->title);
    }

    public function test_now_playing_dto_maps_api_response(): void
    {
        $apiData = [
            'now_playing' => [
                'song' => [
                    'id' => 'current123',
                    'title' => 'Current Song',
                    'artist' => 'Current Artist',
                ],
                'elapsed' => 60,
                'remaining' => 120,
                'duration' => 180,
                'played_at' => 1609480800,
            ],
            'playing_next' => [
                'song' => [
                    'id' => 'next123',
                    'title' => 'Next Song',
                    'artist' => 'Next Artist',
                ],
            ],
            'live' => [
                'is_live' => true,
                'streamer_name' => 'DJ Live',
            ],
            'listeners' => [
                'current' => 100,
                'unique' => 75,
                'total' => 100,
            ],
            'is_online' => true,
        ];

        $nowPlaying = NowPlayingDTO::fromApi($apiData);

        $this->assertEquals('current123', $nowPlaying->currentSong->id);
        $this->assertEquals('Current Song', $nowPlaying->currentSong->title);
        $this->assertEquals('next123', $nowPlaying->nextSong->id);
        $this->assertEquals('Next Song', $nowPlaying->nextSong->title);
        $this->assertEquals(60, $nowPlaying->elapsed);
        $this->assertEquals(120, $nowPlaying->remaining);
        $this->assertEquals(180, $nowPlaying->duration);
        $this->assertTrue($nowPlaying->isLive);
        $this->assertEquals(100, $nowPlaying->listeners);
        $this->assertEquals(75, $nowPlaying->uniqueListeners);
        $this->assertTrue($nowPlaying->isOnline);
        $this->assertEquals('DJ Live', $nowPlaying->streamerName);
    }

    public function test_now_playing_dto_handles_missing_next_song(): void
    {
        $apiData = [
            'now_playing' => [
                'song' => [
                    'id' => 'current123',
                    'title' => 'Current Song',
                    'artist' => 'Current Artist',
                ],
                'elapsed' => 60,
                'remaining' => 120,
                'played_at' => 1609480800,
            ],
            'playing_next' => null,
            'live' => [
                'is_live' => false,
            ],
            'listeners' => [
                'current' => 50,
            ],
            'is_online' => true,
        ];

        $nowPlaying = NowPlayingDTO::fromApi($apiData);

        $this->assertNull($nowPlaying->nextSong);
        $this->assertFalse($nowPlaying->isLive);
    }

    public function test_now_playing_dto_progress_percentage(): void
    {
        $apiData = [
            'now_playing' => [
                'song' => [
                    'id' => 'test',
                    'title' => 'Test',
                    'artist' => 'Test',
                ],
                'elapsed' => 90,
                'remaining' => 90,
                'duration' => 180,
                'played_at' => time(),
            ],
            'live' => ['is_live' => false],
            'listeners' => ['current' => 10],
            'is_online' => true,
        ];

        $nowPlaying = NowPlayingDTO::fromApi($apiData);

        $this->assertEquals(50.0, $nowPlaying->progressPercentage());
    }

    public function test_dto_to_array_methods(): void
    {
        $song = SongDTO::fromApi([
            'id' => 'test',
            'title' => 'Test Song',
            'artist' => 'Test Artist',
            'genre' => 'Pop',
            'isrc' => 'ABC123',
        ]);

        $array = $song->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('title', $array);
        $this->assertArrayHasKey('artist', $array);
        $this->assertArrayHasKey('genre', $array);
        $this->assertArrayHasKey('isrc', $array);
        $this->assertEquals('test', $array['id']);
    }

    public function test_playlist_dto_maps_api_response(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Top Hits',
            'short_name' => 'top_hits',
            'type' => 'default',
            'source' => 'songs',
            'order' => 1,
            'is_enabled' => true,
            'is_jingle' => false,
            'weight' => 3,
            'schedule_items' => [
                [
                    'start_time' => '0800',
                    'end_time' => '1200',
                    'days' => [1, 2, 3, 4, 5],
                ],
            ],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);

        $this->assertEquals(1, $playlist->id);
        $this->assertEquals('Top Hits', $playlist->name);
        $this->assertEquals('top_hits', $playlist->shortName);
        $this->assertEquals('default', $playlist->type);
        $this->assertEquals('songs', $playlist->source);
        $this->assertEquals(1, $playlist->order);
        $this->assertTrue($playlist->isEnabled);
        $this->assertFalse($playlist->isJingle);
        $this->assertEquals(3, $playlist->weight);
        $this->assertCount(1, $playlist->scheduleItems);
    }

    public function test_playlist_dto_handles_missing_fields(): void
    {
        $apiData = [
            'id' => 2,
            'name' => 'Simple Playlist',
        ];

        $playlist = PlaylistDTO::fromApi($apiData);

        $this->assertEquals(2, $playlist->id);
        $this->assertEquals('Simple Playlist', $playlist->name);
        $this->assertNull($playlist->shortName);
        $this->assertEquals('default', $playlist->type);
        $this->assertEquals('songs', $playlist->source);
        $this->assertEquals(0, $playlist->order);
        $this->assertFalse($playlist->isEnabled);
        $this->assertFalse($playlist->isJingle);
        $this->assertNull($playlist->weight);
    }

    public function test_playlist_dto_formatted_schedule(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Morning Show',
            'is_enabled' => true,
            'schedule_items' => [
                [
                    'start_time' => '0800',
                    'end_time' => '1200',
                    'days' => [1, 2, 3],
                ],
            ],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $schedule = $playlist->getFormattedSchedule();

        $this->assertCount(3, $schedule);
        $this->assertEquals('Monday', $schedule[0]['day']);
        $this->assertEquals('8:00 AM', $schedule[0]['start_time']);
        $this->assertEquals('12:00 PM', $schedule[0]['end_time']);
    }

    public function test_playlist_dto_to_array(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Test Playlist',
            'is_enabled' => true,
            'is_jingle' => false,
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $array = $playlist->toArray();

        $this->assertArrayHasKey('id', $array);
        $this->assertArrayHasKey('name', $array);
        $this->assertArrayHasKey('is_enabled', $array);
        $this->assertArrayHasKey('is_jingle', $array);
        $this->assertArrayHasKey('formatted_schedule', $array);
        $this->assertArrayHasKey('is_currently_active', $array);
    }

    public function test_playlist_dto_handles_invalid_time_formats(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Test Playlist',
            'is_enabled' => true,
            'schedule_items' => [
                [
                    'start_time' => '9999', // Invalid hours
                    'end_time' => '2560',   // Invalid minutes
                    'days' => [1],
                ],
            ],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $schedule = $playlist->getFormattedSchedule();

        // Should handle gracefully with corrected values
        $this->assertCount(1, $schedule);
        $this->assertEquals('Monday', $schedule[0]['day']);
    }

    public function test_playlist_dto_handles_non_numeric_time(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Test Playlist',
            'is_enabled' => true,
            'schedule_items' => [
                [
                    'start_time' => 'invalid',
                    'end_time' => 'time',
                    'days' => [1],
                ],
            ],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $schedule = $playlist->getFormattedSchedule();

        // Should handle gracefully with default time
        $this->assertCount(1, $schedule);
        $this->assertEquals('12:00 AM', $schedule[0]['start_time']);
        $this->assertEquals('12:00 AM', $schedule[0]['end_time']);
    }

    public function test_playlist_dto_handles_invalid_day_numbers(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Test Playlist',
            'is_enabled' => true,
            'schedule_items' => [
                [
                    'start_time' => '0800',
                    'end_time' => '1200',
                    'days' => [-1, 7, 'invalid', null, 1], // Invalid days mixed with valid
                ],
            ],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $schedule = $playlist->getFormattedSchedule();

        // Should only include valid day (1 = Monday)
        $this->assertCount(1, $schedule);
        $this->assertEquals('Monday', $schedule[0]['day']);
    }

    public function test_playlist_dto_handles_string_day_numbers(): void
    {
        // JSON may deserialize integers as strings
        $apiData = [
            'id' => 1,
            'name' => 'Test Playlist',
            'is_enabled' => true,
            'schedule_items' => [
                [
                    'start_time' => '0800',
                    'end_time' => '1200',
                    'days' => ['1', '2', '3'], // Days as strings (from JSON)
                ],
            ],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $schedule = $playlist->getFormattedSchedule();

        // Should handle string day numbers correctly
        $this->assertCount(3, $schedule);
        $this->assertEquals('Monday', $schedule[0]['day']);
        $this->assertEquals('Tuesday', $schedule[1]['day']);
        $this->assertEquals('Wednesday', $schedule[2]['day']);
    }

    public function test_playlist_dto_handles_empty_schedule_items(): void
    {
        $apiData = [
            'id' => 1,
            'name' => 'Test Playlist',
            'is_enabled' => true,
            'schedule_items' => [],
        ];

        $playlist = PlaylistDTO::fromApi($apiData);
        $schedule = $playlist->getFormattedSchedule();

        $this->assertEmpty($schedule);
        // Playlist with no schedule should be active if enabled
        $this->assertTrue($playlist->isCurrentlyActive());
    }
}
