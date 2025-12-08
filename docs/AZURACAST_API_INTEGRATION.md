# AzuraCast API Integration Documentation

This document provides comprehensive documentation of the AzuraCast API integration used in the Los Santos Radio application.

## Overview

Los Santos Radio integrates with AzuraCast to provide radio station data including:
- Now playing information (single station and all stations)
- Song history
- Song request functionality
- Station metadata (single station and all public stations)
- Playlist management

## Configuration

AzuraCast API configuration is stored in `config/services.php`:

```php
'azuracast' => [
    'base_url' => env('AZURACAST_BASE_URL'),
    'api_key' => env('AZURACAST_API_KEY'),
    'station_id' => env('AZURACAST_STATION_ID', 1),
    'cache_ttl' => env('AZURACAST_CACHE_TTL', 30), // seconds
],
```

### Environment Variables

| Variable | Description | Required |
|----------|-------------|----------|
| `AZURACAST_BASE_URL` | Base URL of your AzuraCast installation | Yes |
| `AZURACAST_API_KEY` | API key for authentication | Yes |
| `AZURACAST_STATION_ID` | Station ID to use | No (default: 1) |
| `AZURACAST_CACHE_TTL` | Cache duration in seconds | No (default: 30) |

## Web GUI Routes

### Stations Page
**Route:** `GET /stations`  
**Name:** `stations`

Displays all public stations with their now playing information, listener counts, and status. Auto-refreshes every 30 seconds.

### Schedule Page
**Route:** `GET /schedule`  
**Name:** `schedule`

Displays active playlists and weekly schedule for the configured station.

## REST API Endpoints

### Public: Now Playing

#### Get Now Playing for Default Station
**Endpoint:** `GET /api/radio/now-playing`  
**Name:** `radio.now-playing`

Returns now playing information for the configured default station.

**Response:**
```json
{
  "success": true,
  "data": {
    "current_song": { ... },
    "next_song": { ... },
    "elapsed": 120,
    "remaining": 180,
    "duration": 300,
    "is_live": false,
    "listeners": 42,
    "unique_listeners": 38,
    "played_at": "2024-12-05T00:00:00+00:00",
    "is_online": true,
    "streamer_name": null
  }
}
```

#### Get Now Playing for All Stations
**Endpoint:** `GET /api/stations/now-playing`  
**Name:** `stations.api.now-playing`

Returns now playing information for all public stations in the AzuraCast instance.

**Response:**
```json
{
  "success": true,
  "data": [
    { /* NowPlayingDTO */ },
    { /* NowPlayingDTO */ }
  ]
}
```

### Public: Stations

#### List All Public Stations
**Endpoint:** `GET /api/stations/`  
**Name:** `stations.api.list`

Returns a list of all public stations from AzuraCast.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "Los Santos Radio",
      "shortcode": "los_santos",
      "description": "The best radio station in San Andreas",
      "url": "https://example.com",
      "listen_url": "https://stream.example.com/radio.mp3",
      "public_playlist_uri": "https://example.com/public/los_santos",
      "is_online": true,
      "enable_requests": true,
      "request_delay": 180,
      "request_threshold": 15
    }
  ]
}
```

### Stations: Playlists

#### List All Playlists
**Endpoint:** `GET /api/playlists/`  
**Name:** `playlists.api.index`

Returns all playlists for the configured station.

**Response:**
```json
{
  "success": true,
  "data": [
    {
      "id": 1,
      "name": "General Rotation",
      "short_name": "general",
      "type": "default",
      "source": "songs",
      "order": 1,
      "is_enabled": true,
      "is_jingle": false,
      "weight": 3,
      "schedule_items": [...],
      "formatted_schedule": [...],
      "is_currently_active": true
    }
  ]
}
```

#### List Active Playlists
**Endpoint:** `GET /api/playlists/active`  
**Name:** `playlists.api.active`

Returns only enabled, non-jingle playlists.

#### List Currently Playing Playlists
**Endpoint:** `GET /api/playlists/current`  
**Name:** `playlists.api.current`

Returns playlists that are currently scheduled to play based on time.

## AzuraCast API Endpoints Used

### 1. Now Playing (Single Station)
**Endpoint:** `GET /api/nowplaying/{station_id}`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `getStationNowPlaying`

Returns the current now playing information for the station.

**Response Schema:** `Api_NowPlaying`
- `station` - Station details
- `listeners` - Listener counts (total, unique, current)
- `live` - Live DJ information
- `now_playing` - Current song with elapsed/remaining time
- `playing_next` - Next queued song
- `song_history` - Recent play history
- `is_online` - Station online status

### 2. Now Playing (All Stations)
**Endpoint:** `GET /api/nowplaying`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `getAllNowPlaying`

Returns now playing information for all public stations.

**Response Schema:** Array of `Api_NowPlaying`

### 3. Station Details
**Endpoint:** `GET /api/station/{station_id}`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `getStation`

Returns basic information about the station.

**Response Schema:** `Api_NowPlaying_Station`
- `id` - Station ID
- `name` - Station name
- `shortcode` - URL-friendly station identifier
- `description` - Station description
- `listen_url` - Primary stream URL
- `public_player_url` - Public player URL
- `requests_enabled` - Whether song requests are enabled

### 4. All Public Stations
**Endpoint:** `GET /api/stations`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `getStations`

Returns a list of all public stations.

**Response Schema:** Array of `Api_NowPlaying_Station`

### 5. Station Playlists
**Endpoint:** `GET /api/station/{station_id}/playlists`  
**Authentication:** Required (X-API-Key header)  
**OpenAPI Reference:** `getPlaylists`

Returns all playlists for the station.

**Response Schema:** Array of `Api_StationPlaylist`
- `id` - Playlist ID
- `name` - Playlist name
- `short_name` - URL-friendly playlist name
- `type` - Playlist type (default, scheduled, once_per_day, etc.)
- `source` - Source type (songs, remote_url, etc.)
- `is_enabled` - Whether the playlist is enabled
- `is_jingle` - Whether this is a jingle playlist
- `weight` - Playlist weight for rotation
- `schedule_items` - Schedule items with days and times

### 6. Song History
**Endpoint:** `GET /api/station/{station_id}/history`  
**Authentication:** Required (X-API-Key header with admin permissions)  
**OpenAPI Reference:** `getStationHistory`

Returns the station's song playback history.

**Note:** This endpoint requires admin-level API key permissions. If the API key doesn't have sufficient permissions, the service automatically falls back to using the song history from the Now Playing endpoint (`/api/nowplaying/{station_id}`), which provides recent history (typically last 5-15 songs) without requiring authentication.

**Query Parameters:**
- `start` - Start date (optional, PHP date format)
- `end` - End date (optional, PHP date format)

**Response Schema:** Array of `Api_DetailedSongHistory`
- `sh_id` - Unique history identifier
- `played_at` - UNIX timestamp of playback
- `duration` - Song duration in seconds
- `playlist` - Source playlist name
- `streamer` - DJ name if applicable
- `is_request` - Whether this was a listener request
- `song` - Song details

**Fallback Behavior:**
The `AzuraCastService::getHistory()` method implements a graceful fallback strategy:
1. First attempts to fetch from `/api/station/{id}/history` (requires admin API key)
2. If that fails with authentication error, falls back to `song_history` from `/api/nowplaying/{id}` (public endpoint)
3. If both fail, returns an empty collection and logs the error

### 7. Requestable Songs
**Endpoint:** `GET /api/station/{station_id}/requests`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `getRequestableSongs`

Returns a list of songs that can be requested.

**Response Schema:** Array of `Api_StationRequest`
- `request_id` - Unique request identifier
- `request_url` - Direct URL to submit request
- `song` - Song details

### 8. Submit Song Request
**Endpoint:** `POST /api/station/{station_id}/request/{request_id}`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `submitSongRequest`

Submits a song request.

**Response:** Success status

### 9. Station Queue (Admin)
**Endpoint:** `GET /api/station/{station_id}/queue`  
**Authentication:** Required (X-API-Key header)  
**OpenAPI Reference:** `getQueue`

Returns the upcoming song playback queue.

**Response Schema:** Array of `Api_StationQueueDetailed`

### 10. Media Files (Admin)
**Endpoint:** `GET /api/station/{station_id}/files`  
**Authentication:** Required (X-API-Key header)  
**OpenAPI Reference:** `getFiles`

Returns all uploaded media files for the station.

**Response Schema:** Array of `Api_StationMedia`

## Data Transfer Objects (DTOs)

### NowPlayingDTO
Maps the `Api_NowPlaying` response.

| Property | Type | API Field |
|----------|------|-----------|
| `currentSong` | SongDTO | `now_playing.song` |
| `nextSong` | ?SongDTO | `playing_next.song` |
| `elapsed` | int | `now_playing.elapsed` |
| `remaining` | int | `now_playing.remaining` |
| `duration` | int | `now_playing.duration` |
| `isLive` | bool | `live.is_live` |
| `listeners` | int | `listeners.current` (fallback: `listeners.total`) |
| `uniqueListeners` | int | `listeners.unique` |
| `playedAt` | Carbon | `now_playing.played_at` |
| `isOnline` | bool | `is_online` |
| `streamerName` | ?string | `live.streamer_name` |

### SongDTO
Maps the `Api_Song` response.

| Property | Type | API Field |
|----------|------|-----------|
| `id` | string | `id` or `song_id` |
| `title` | string | `title` or `text` |
| `artist` | string | `artist` |
| `album` | ?string | `album` |
| `art` | ?string | `art` |
| `lyrics` | ?string | `lyrics` |
| `duration` | ?int | `duration` |
| `isRequestable` | ?bool | `song_request_enabled` or `is_requestable` |
| `uniqueId` | ?string | `unique_id` |
| `genre` | ?string | `genre` |
| `isrc` | ?string | `isrc` |

### SongHistoryDTO
Maps the `Api_NowPlaying_SongHistory` / `Api_DetailedSongHistory` response.

| Property | Type | API Field |
|----------|------|-----------|
| `id` | int | `sh_id` |
| `song` | SongDTO | `song` |
| `playedAt` | Carbon | `played_at` |
| `duration` | int | `duration` |
| `playlist` | ?string | `playlist` |
| `dj` | ?string | `streamer` |
| `isRequest` | bool | `is_request` |

### StationDTO
Maps the `Api_NowPlaying_Station` response.

| Property | Type | API Field |
|----------|------|-----------|
| `id` | int | `id` |
| `name` | string | `name` |
| `shortcode` | string | `shortcode` |
| `description` | ?string | `description` |
| `url` | ?string | `url` |
| `listenUrl` | ?string | `listen_url` |
| `publicPlaylistUri` | ?string | `public_player_url` |
| `isOnline` | bool | `is_online` |
| `enableRequests` | bool | `requests_enabled` (or legacy `enable_requests`) |
| `requestDelay` | int | `request_delay` |
| `requestThreshold` | int | `request_threshold` |

### PlaylistDTO
Maps the `Api_StationPlaylist` response.

| Property | Type | API Field |
|----------|------|-----------|
| `id` | int | `id` |
| `name` | string | `name` |
| `shortName` | ?string | `short_name` |
| `type` | string | `type` |
| `source` | string | `source` |
| `order` | int | `order` |
| `isEnabled` | bool | `is_enabled` |
| `isJingle` | bool | `is_jingle` |
| `weight` | ?int | `weight` |
| `scheduleItems` | ?array | `schedule_items` |
| `playOnceTime` | ?string | `play_once_time` |
| `playPerMinutes` | ?int | `play_per_minutes` |
| `playPerSongs` | ?int | `play_per_songs` |
| `playPerHourMinute` | ?int | `play_per_hour_minute` |

**Methods:**
- `getFormattedSchedule()` - Returns formatted schedule with day names and AM/PM times
- `isCurrentlyActive()` - Checks if playlist is scheduled for current time

## Error Handling

The `AzuraCastException` class provides typed exceptions:

- `AzuraCastException::connectionFailed()` - Connection issues
- `AzuraCastException::requestFailed()` - API request errors
- `AzuraCastException::invalidResponse()` - Invalid response format
- `AzuraCastException::notConfigured()` - Missing configuration

## Caching Strategy

API responses are cached to reduce load:

| Endpoint | Cache TTL | Cache Key Pattern |
|----------|-----------|-------------------|
| Now Playing (Single) | 30s (configurable) | `azuracast.nowplaying.{station_id}` |
| Now Playing (All) | 30s (configurable) | `azuracast.nowplaying.all` |
| Station | 5 minutes | `azuracast.station.{station_id}` |
| All Stations | 5 minutes | `azuracast.stations.all` |
| Playlists | 5 minutes | `azuracast.playlists.{station_id}` |
| History | 30s (configurable) | `azuracast.history.{station_id}.{limit}` |
| Request Queue | 30s (configurable) | `azuracast.requests.queue.{station_id}` |
| Requestable Songs | 60s | `azuracast.requests.songs.{station_id}.{perPage}.{page}.{searchHash}` |
| Library Search | 60s | `azuracast.library.search.{queryHash}.{limit}` |

## OpenAPI Specification Reference

This integration is validated against the official AzuraCast OpenAPI specification:
https://raw.githubusercontent.com/AzuraCast/AzuraCast/main/web/static/openapi.yml

**Validated Version:** AzuraCast 0.23.1+

## Changelog

### December 2024 - Full API Integration
- Added `getAllStations()` method for Public: Stations API
- Added `getAllNowPlaying()` method for all stations now playing
- Added Stations page (`/stations`) to display all public stations
- Added REST API endpoints:
  - `GET /api/stations/` - List all public stations
  - `GET /api/stations/now-playing` - Now playing for all stations
  - `GET /api/playlists/` - List all playlists
  - `GET /api/playlists/active` - List enabled non-jingle playlists
  - `GET /api/playlists/current` - List currently scheduled playlists
- Added StationsController and PlaylistsController
- Added navigation link to Stations page
- Updated documentation with full API reference

### Initial Documentation (December 2024)
- Updated `StationDTO` to support both `requests_enabled` (official) and `enable_requests` (legacy)
- Added `genre` and `isrc` fields to `SongDTO` per API spec
- Added `isRequest` field to `SongHistoryDTO` per API spec
- Added `uniqueListeners`, `isOnline`, and `streamerName` fields to `NowPlayingDTO`
- Created comprehensive API documentation
