<?php

namespace App\Services;

use App\DTOs\NowPlayingDTO;
use App\DTOs\PlaylistDTO;
use App\DTOs\SongDTO;
use App\DTOs\SongHistoryDTO;
use App\DTOs\StationDTO;
use App\Events\NowPlayingUpdated;
use App\Exceptions\AzuraCastException;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AzuraCastService
{
    protected string $baseUrl;

    protected string $apiKey;

    protected int $stationId;

    protected int $cacheTtl;

    protected PendingRequest $http;

    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->baseUrl = rtrim(config('services.azuracast.base_url', ''), '/');
        $this->apiKey = config('services.azuracast.api_key', '');
        $this->stationId = (int) config('services.azuracast.station_id', 1);
        $this->cacheTtl = (int) config('services.azuracast.cache_ttl', 30);
        $this->cacheService = $cacheService;

        $this->http = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->timeout(10)
            ->retry(3, 100, function ($exception) {
                return $exception instanceof \Illuminate\Http\Client\ConnectionException;
            }, throw: false);
    }

    /**
     * Check if the service is properly configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->baseUrl) && ! empty($this->apiKey);
    }

    /**
     * Get the current now playing information.
     */
    public function getNowPlaying(): NowPlayingDTO
    {
        return $this->cacheService->remember(
            CacheService::NAMESPACE_RADIO,
            "nowplaying.{$this->stationId}",
            $this->cacheTtl,
            function () {
                $data = $this->makeRequest("/api/nowplaying/{$this->stationId}");
                $nowPlaying = NowPlayingDTO::fromApi($data);
                
                // Check if song has changed and broadcast update
                $previousData = $this->cacheService->get(
                    CacheService::NAMESPACE_RADIO,
                    "nowplaying.{$this->stationId}.previous"
                );
                
                $currentSongId = $nowPlaying->currentSong->id ?? null;
                $previousSongId = $previousData['current_song_id'] ?? null;
                
                if ($currentSongId !== $previousSongId) {
                    // Song changed, broadcast update
                    event(new NowPlayingUpdated($nowPlaying, $this->stationId));
                    
                    // Store current song ID for next comparison
                    $this->cacheService->put(
                        CacheService::NAMESPACE_RADIO,
                        "nowplaying.{$this->stationId}.previous",
                        ['current_song_id' => $currentSongId],
                        CacheService::TTL_REALTIME * 2
                    );
                }
                
                return $nowPlaying;
            }
        );
    }

    /**
     * Get station details.
     */
    public function getStation(): StationDTO
    {
        $cacheKey = "azuracast.station.{$this->stationId}";

        $data = Cache::remember($cacheKey, 300, function () {
            return $this->makeRequest("/api/station/{$this->stationId}");
        });

        return StationDTO::fromApi($data);
    }

    /**
     * Get all public stations.
     *
     * Fetches all public stations from the AzuraCast instance.
     *
     * @return Collection<int, StationDTO>
     */
    public function getAllStations(): Collection
    {
        $cacheKey = 'azuracast.stations.all';

        $data = Cache::remember($cacheKey, 300, function () {
            return $this->makeRequest('/api/stations');
        });

        // Handle paginated response format (with 'items' key) or plain array
        $items = $this->extractItems($data);

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => StationDTO::fromApi($item));
    }

    /**
     * Get now playing data for all stations.
     *
     * Fetches now playing information for all public stations.
     *
     * @return Collection<int, NowPlayingDTO>
     */
    public function getAllNowPlaying(): Collection
    {
        $cacheKey = 'azuracast.nowplaying.all';

        $data = Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->makeRequest('/api/nowplaying');
        });

        // Handle paginated response format (with 'items' key) or plain array
        $items = $this->extractItems($data);

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => NowPlayingDTO::fromApi($item));
    }

    /**
     * Get station playlists.
     *
     * Fetches all playlists for the station using the AzuraCast API.
     *
     * @return Collection<int, PlaylistDTO>
     */
    public function getPlaylists(): Collection
    {
        $cacheKey = "azuracast.playlists.{$this->stationId}";

        return Cache::remember($cacheKey, 300, function () {
            try {
                // Try to fetch from the dedicated playlists endpoint (requires admin API key)
                $data = $this->makeRequest("/api/station/{$this->stationId}/playlists");

                // Handle paginated response format (with 'items' key) or plain array
                $items = $this->extractItems($data);

                return collect($items)
                    ->filter(fn ($item) => is_array($item))
                    ->map(fn ($item) => PlaylistDTO::fromApi($item));
            } catch (AzuraCastException $e) {
                // Playlists endpoint requires admin access - no public fallback available
                Log::info('Playlists endpoint not accessible (requires admin API key)', [
                    'station_id' => $this->stationId,
                    'error' => $e->getMessage(),
                ]);

                // Return empty collection
                return collect();
            }
        });
    }

    /**
     * Get song history.
     *
     * @return Collection<int, SongHistoryDTO>
     */
    public function getHistory(int $limit = 20): Collection
    {
        $cacheKey = "azuracast.history.{$this->stationId}.{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit) {
            try {
                // Try to fetch from the dedicated history endpoint (requires admin API key)
                $data = $this->makeRequest("/api/station/{$this->stationId}/history", [
                    'limit' => $limit,
                ]);

                // Handle paginated response format (with 'items' key) or plain array
                $items = $this->extractItems($data);

                return collect($items)
                    ->filter(fn ($item) => is_array($item))
                    ->map(fn ($item) => SongHistoryDTO::fromApi($item))
                    ->take($limit);
            } catch (AzuraCastException $e) {
                // Fallback: Get history from now playing endpoint (public endpoint)
                Log::info('History endpoint not accessible, falling back to now playing history', [
                    'station_id' => $this->stationId,
                    'error' => $e->getMessage(),
                ]);

                try {
                    $nowPlayingData = $this->makeRequest("/api/nowplaying/{$this->stationId}");
                    $historyItems = $nowPlayingData['song_history'] ?? [];

                    return collect($historyItems)
                        ->filter(fn ($item) => is_array($item))
                        ->map(fn ($item) => SongHistoryDTO::fromApi($item))
                        ->take($limit);
                } catch (\Exception $fallbackException) {
                    Log::warning('Failed to fetch history from both endpoints', [
                        'station_id' => $this->stationId,
                        'error' => $fallbackException->getMessage(),
                    ]);

                    // Return empty collection on complete failure
                    return collect();
                }
            }
        });
    }

    /**
     * Get the song request queue.
     *
     * @return Collection<int, SongDTO>
     */
    public function getRequestQueue(): Collection
    {
        $cacheKey = "azuracast.requests.queue.{$this->stationId}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () {
            try {
                // Try to fetch from the dedicated queue endpoint (requires admin API key)
                $data = $this->makeRequest("/api/station/{$this->stationId}/queue");

                // Handle paginated response format (with 'items' key) or plain array
                $items = $this->extractItems($data);

                return collect($items)
                    ->filter(fn ($item) => is_array($item))
                    ->map(fn ($item) => SongDTO::fromApi($item['song'] ?? $item));
            } catch (AzuraCastException $e) {
                // Fallback: Get upcoming songs from now playing endpoint (public endpoint)
                Log::info('Queue endpoint not accessible, falling back to now playing queue', [
                    'station_id' => $this->stationId,
                    'error' => $e->getMessage(),
                ]);

                try {
                    $nowPlayingData = $this->makeRequest("/api/nowplaying/{$this->stationId}");
                    $playingNext = $nowPlayingData['playing_next'] ?? null;

                    // Now playing endpoint only provides the next song, not full queue
                    if ($playingNext && isset($playingNext['song'])) {
                        return collect([SongDTO::fromApi($playingNext['song'])]);
                    }

                    return collect();
                } catch (\Exception $fallbackException) {
                    Log::warning('Failed to fetch queue from both endpoints', [
                        'station_id' => $this->stationId,
                        'error' => $fallbackException->getMessage(),
                    ]);

                    // Return empty collection on complete failure
                    return collect();
                }
            }
        });
    }

    /**
     * Get requestable songs from the library.
     *
     * @return Collection<int, SongDTO>
     */
    public function getRequestableSongs(int $perPage = 50, int $page = 1, ?string $search = null): array
    {
        $cacheKey = "azuracast.requests.songs.{$this->stationId}.{$perPage}.{$page}.".md5($search ?? '');

        return Cache::remember($cacheKey, $this->cacheTtl * 2, function () use ($perPage, $page, $search) {
            $params = [
                'per_page' => $perPage,
                'page' => $page,
            ];

            if ($search) {
                $params['searchPhrase'] = $search;
            }

            $data = $this->makeRequest("/api/station/{$this->stationId}/requests", $params);

            // Handle paginated response format (with 'items' key) or plain array
            $items = $this->extractItems($data);
            $total = $data['meta']['total'] ?? $data['total'] ?? count($items);

            return [
                'songs' => collect($items)
                    ->filter(fn ($item) => is_array($item))
                    ->map(fn ($item) => SongDTO::fromApi($item['song'] ?? $item)),
                'total' => $total,
            ];
        });
    }

    /**
     * Submit a song request.
     */
    public function submitRequest(string $songId): array
    {
        // Clear relevant caches when a request is made
        Cache::forget("azuracast.requests.queue.{$this->stationId}");

        try {
            $response = $this->http->post("/api/station/{$this->stationId}/request/{$songId}");

            if ($response->successful()) {
                return [
                    'success' => true,
                    'message' => $response->json('message', 'Request submitted successfully'),
                ];
            }

            // Handle specific HTTP errors with better messages
            $errorMessage = match ($response->status()) {
                404 => 'This song is not available for requests or was not found in the library.',
                429 => 'Too many requests. Please wait before requesting another song.',
                400 => $response->json('message', 'This song cannot be requested at this time.'),
                default => $response->json('message', 'Failed to submit request. Please try again.'),
            };

            return [
                'success' => false,
                'message' => $errorMessage,
            ];
        } catch (\Illuminate\Http\Client\RequestException $e) {
            Log::warning('Song request failed', [
                'song_id' => $songId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Unable to submit request. The song may not be available or the service is temporarily unavailable.',
            ];
        } catch (\Exception $e) {
            Log::error('Song request exception', [
                'song_id' => $songId,
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'An error occurred while submitting your request. Please try again later.',
            ];
        }
    }

    /**
     * Search the song library.
     *
     * @return Collection<int, SongDTO>
     */
    public function searchLibrary(string $query, int $limit = 25): Collection
    {
        $cacheKey = 'azuracast.library.search.'.md5($query).".{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl * 2, function () use ($query, $limit) {
            try {
                // Try to search the media files endpoint (requires admin API key)
                $data = $this->makeRequest("/api/station/{$this->stationId}/files", [
                    'searchPhrase' => $query,
                    'per_page' => $limit,
                ]);

                // Handle paginated response format (with 'items' key) or plain array
                $items = $this->extractItems($data);

                return collect($items)
                    ->filter(fn ($item) => is_array($item))
                    ->map(fn ($item) => SongDTO::fromApi($item));
            } catch (AzuraCastException $e) {
                // Fallback: Use the requestable songs endpoint (public)
                Log::info('Files endpoint not accessible, falling back to requestable songs', [
                    'station_id' => $this->stationId,
                    'query' => $query,
                    'error' => $e->getMessage(),
                ]);

                try {
                    $result = $this->getRequestableSongs($limit, 1, $query);
                    return $result['songs'] ?? collect();
                } catch (\Exception $fallbackException) {
                    Log::warning('Failed to search library from both endpoints', [
                        'station_id' => $this->stationId,
                        'query' => $query,
                        'error' => $fallbackException->getMessage(),
                    ]);

                    // Return empty collection on complete failure
                    return collect();
                }
            }
        });
    }

    /**
     * Clear all AzuraCast caches.
     */
    public function clearCache(): void
    {
        $keys = [
            "azuracast.nowplaying.{$this->stationId}",
            "azuracast.station.{$this->stationId}",
            "azuracast.history.{$this->stationId}.*",
            "azuracast.requests.queue.{$this->stationId}",
        ];

        foreach ($keys as $key) {
            if (str_contains($key, '*')) {
                // For wildcard patterns, we'd need to use tagged caching or pattern deletion
                // For simplicity, we'll skip wildcards here
                continue;
            }
            Cache::forget($key);
        }
    }

    /**
     * Extract items from API response, handling both paginated and plain array formats.
     *
     * @param  array  $data  API response data
     * @return array Items array
     */
    protected function extractItems(array $data): array
    {
        // Check for paginated response format with 'items' key
        if (isset($data['items']) && is_array($data['items'])) {
            return $data['items'];
        }

        // Check for paginated response format with 'data' key (alternative format)
        if (isset($data['data']) && is_array($data['data'])) {
            return $data['data'];
        }

        // Check for paginated response format with 'rows' key (AzuraCast requests endpoint)
        if (isset($data['rows']) && is_array($data['rows'])) {
            return $data['rows'];
        }

        // If response has 'meta' or 'links' keys, it's a paginated response without items
        // This shouldn't normally happen, but handle it gracefully
        if (isset($data['meta']) || isset($data['links'])) {
            return [];
        }

        // Plain array response - return as-is
        return $data;
    }

    /**
     * Make a request to the AzuraCast API.
     *
     * @throws AzuraCastException
     */
    protected function makeRequest(string $endpoint, array $params = []): array
    {
        if (! $this->isConfigured()) {
            throw AzuraCastException::notConfigured();
        }

        try {
            $response = $this->http->get($endpoint, $params);

            if ($response->failed()) {
                Log::error('AzuraCast API request failed', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                throw AzuraCastException::requestFailed(
                    $response->json('message', 'Unknown error'),
                    $response->status()
                );
            }

            return $response->json() ?? [];
        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            Log::error('AzuraCast API connection failed', [
                'endpoint' => $endpoint,
                'error' => $e->getMessage(),
            ]);

            throw AzuraCastException::connectionFailed($e->getMessage());
        }
    }
}
