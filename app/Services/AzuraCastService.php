<?php

namespace App\Services;

use App\DTOs\NowPlayingDTO;
use App\DTOs\PlaylistDTO;
use App\DTOs\SongDTO;
use App\DTOs\SongHistoryDTO;
use App\DTOs\StationDTO;
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

    public function __construct()
    {
        $this->baseUrl = rtrim(config('services.azuracast.base_url', ''), '/');
        $this->apiKey = config('services.azuracast.api_key', '');
        $this->stationId = (int) config('services.azuracast.station_id', 1);
        $this->cacheTtl = (int) config('services.azuracast.cache_ttl', 30);

        $this->http = Http::baseUrl($this->baseUrl)
            ->withHeaders([
                'X-API-Key' => $this->apiKey,
                'Accept' => 'application/json',
            ])
            ->timeout(10)
            ->retry(3, 100, function ($exception) {
                return $exception instanceof \Illuminate\Http\Client\ConnectionException;
            });
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
        $cacheKey = "azuracast.nowplaying.{$this->stationId}";

        $data = Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->makeRequest("/api/nowplaying/{$this->stationId}");
        });

        return NowPlayingDTO::fromApi($data);
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

        $data = Cache::remember($cacheKey, 300, function () {
            return $this->makeRequest("/api/station/{$this->stationId}/playlists");
        });

        // Handle paginated response format (with 'items' key) or plain array
        $items = $this->extractItems($data);

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => PlaylistDTO::fromApi($item));
    }

    /**
     * Get song history.
     *
     * @return Collection<int, SongHistoryDTO>
     */
    public function getHistory(int $limit = 20): Collection
    {
        $cacheKey = "azuracast.history.{$this->stationId}.{$limit}";

        $data = Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit) {
            return $this->makeRequest("/api/station/{$this->stationId}/history", [
                'limit' => $limit,
            ]);
        });

        // Handle paginated response format (with 'items' key) or plain array
        $items = $this->extractItems($data);

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => SongHistoryDTO::fromApi($item));
    }

    /**
     * Get the song request queue.
     *
     * @return Collection<int, SongDTO>
     */
    public function getRequestQueue(): Collection
    {
        $cacheKey = "azuracast.requests.queue.{$this->stationId}";

        $data = Cache::remember($cacheKey, $this->cacheTtl, function () {
            return $this->makeRequest("/api/station/{$this->stationId}/queue");
        });

        // Handle paginated response format (with 'items' key) or plain array
        $items = $this->extractItems($data);

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => SongDTO::fromApi($item['song'] ?? $item));
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

        $response = $this->http->post("/api/station/{$this->stationId}/request/{$songId}");

        if ($response->successful()) {
            return [
                'success' => true,
                'message' => $response->json('message', 'Request submitted successfully'),
            ];
        }

        return [
            'success' => false,
            'message' => $response->json('message', 'Failed to submit request'),
        ];
    }

    /**
     * Search the song library.
     *
     * @return Collection<int, SongDTO>
     */
    public function searchLibrary(string $query, int $limit = 25): Collection
    {
        $cacheKey = 'azuracast.library.search.'.md5($query).".{$limit}";

        $data = Cache::remember($cacheKey, $this->cacheTtl * 2, function () use ($query, $limit) {
            return $this->makeRequest("/api/station/{$this->stationId}/files", [
                'searchPhrase' => $query,
                'per_page' => $limit,
            ]);
        });

        // Handle paginated response format (with 'items' key) or plain array
        $items = $this->extractItems($data);

        return collect($items)
            ->filter(fn ($item) => is_array($item))
            ->map(fn ($item) => SongDTO::fromApi($item));
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
