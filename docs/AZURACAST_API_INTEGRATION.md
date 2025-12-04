# AzuraCast API Integration Documentation

This document provides comprehensive documentation of the AzuraCast API integration used in the Los Santos Radio application.

## Overview

Los Santos Radio integrates with AzuraCast to provide radio station data including:
- Now playing information
- Song history
- Song request functionality
- Station metadata

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

## API Endpoints Used

### 1. Now Playing
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

### 2. Station Details
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

### 3. Song History
**Endpoint:** `GET /api/station/{station_id}/history`  
**Authentication:** Required (X-API-Key header)  
**OpenAPI Reference:** `getStationHistory`

Returns the station's song playback history.

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

### 4. Requestable Songs
**Endpoint:** `GET /api/station/{station_id}/requests`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `getRequestableSongs`

Returns a list of songs that can be requested.

**Response Schema:** Array of `Api_StationRequest`
- `request_id` - Unique request identifier
- `request_url` - Direct URL to submit request
- `song` - Song details

### 5. Submit Song Request
**Endpoint:** `POST /api/station/{station_id}/request/{request_id}`  
**Authentication:** Not required (public endpoint)  
**OpenAPI Reference:** `submitSongRequest`

Submits a song request.

**Response:** Success status

### 6. Station Queue (Admin)
**Endpoint:** `GET /api/station/{station_id}/queue`  
**Authentication:** Required (X-API-Key header)  
**OpenAPI Reference:** `getQueue`

Returns the upcoming song playback queue.

**Response Schema:** Array of `Api_StationQueueDetailed`

### 7. Media Files (Admin)
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
| `listeners` | int | `listeners.current` or `listeners.total` |
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
| Now Playing | 30s (configurable) | `azuracast.nowplaying.{station_id}` |
| Station | 5 minutes | `azuracast.station.{station_id}` |
| History | 30s (configurable) | `azuracast.history.{station_id}.{limit}` |
| Request Queue | 30s (configurable) | `azuracast.requests.queue.{station_id}` |
| Requestable Songs | 60s | `azuracast.requests.songs.{station_id}.{perPage}.{page}.{searchHash}` |
| Library Search | 60s | `azuracast.library.search.{queryHash}.{limit}` |

## OpenAPI Specification Reference

This integration is validated against the official AzuraCast OpenAPI specification:
https://raw.githubusercontent.com/AzuraCast/AzuraCast/main/web/static/openapi.yml

**Validated Version:** AzuraCast 0.23.1+

## Changelog

### Initial Documentation (December 2024)
- Updated `StationDTO` to support both `requests_enabled` (official) and `enable_requests` (legacy)
- Added `genre` and `isrc` fields to `SongDTO` per API spec
- Added `isRequest` field to `SongHistoryDTO` per API spec
- Added `uniqueListeners`, `isOnline`, and `streamerName` fields to `NowPlayingDTO`
- Created comprehensive API documentation
