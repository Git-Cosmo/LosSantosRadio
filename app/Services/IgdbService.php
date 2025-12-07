<?php

namespace App\Services;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class IgdbService
{
    protected string $baseUrl = 'https://api.igdb.com/v4';

    protected string $authUrl = 'https://id.twitch.tv/oauth2/token';

    protected int $cacheTtl = 43200; // 12 hours

    protected HttpClientService $httpClient;

    protected ?string $clientId;

    protected ?string $clientSecret;

    public function __construct(HttpClientService $httpClient)
    {
        $this->httpClient = $httpClient;
        $this->clientId = config('services.igdb.client_id');
        $this->clientSecret = config('services.igdb.client_secret');
    }

    /**
     * Check if IGDB is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->clientId) && ! empty($this->clientSecret);
    }

    /**
     * Get access token from Twitch OAuth.
     */
    protected function getAccessToken(): ?string
    {
        if (! $this->isConfigured()) {
            Log::warning('IGDB: Client ID or Secret not configured');

            return null;
        }

        return Cache::remember('igdb.access_token', 86400, function () {
            try {
                $response = $this->httpClient->post($this->authUrl, [
                    'form_params' => [
                        'client_id' => $this->clientId,
                        'client_secret' => $this->clientSecret,
                        'grant_type' => 'client_credentials',
                    ],
                ]);

                if (! $response || $response->getStatusCode() !== 200) {
                    Log::error('IGDB: Failed to get access token', [
                        'status' => $response ? $response->getStatusCode() : 'no response',
                    ]);

                    return null;
                }

                $data = json_decode($response->getBody()->getContents(), true);

                return $data['access_token'] ?? null;
            } catch (\Exception $e) {
                Log::error('IGDB: Error getting access token', ['error' => $e->getMessage()]);

                return null;
            }
        });
    }

    /**
     * Make an API request to IGDB.
     */
    protected function apiRequest(string $endpoint, string $body): ?array
    {
        $accessToken = $this->getAccessToken();

        if (! $accessToken) {
            return null;
        }

        try {
            $response = $this->httpClient->post("{$this->baseUrl}/{$endpoint}", [
                'headers' => [
                    'Client-ID' => $this->clientId,
                    'Authorization' => "Bearer {$accessToken}",
                    'Content-Type' => 'text/plain',
                ],
                'body' => $body,
            ]);

            if (! $response || $response->getStatusCode() !== 200) {
                Log::error("IGDB: Failed to fetch {$endpoint}", [
                    'status' => $response ? $response->getStatusCode() : 'no response',
                ]);

                return null;
            }

            return json_decode($response->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error("IGDB: Error fetching {$endpoint}", ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Search for games by title.
     */
    public function searchGames(string $query, int $limit = 10): Collection
    {
        $cacheKey = 'igdb.search.'.md5($query).".{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl / 4, function () use ($query, $limit) {
            $body = "search \"{$query}\"; fields name,summary,cover.url,first_release_date,genres.name,platforms.name,rating,rating_count,slug,url; limit {$limit};";
            $data = $this->apiRequest('games', $body);

            return $data ? collect($data) : collect();
        });
    }

    /**
     * Get game details by IGDB ID.
     */
    public function getGameById(int $igdbId): ?array
    {
        $cacheKey = "igdb.game.{$igdbId}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($igdbId) {
            $body = "fields name,summary,storyline,cover.url,screenshots.url,first_release_date,genres.name,platforms.name,rating,rating_count,aggregated_rating,aggregated_rating_count,slug,url,websites.url,websites.category; where id = {$igdbId};";

            $data = $this->apiRequest('games', $body);

            return $data && isset($data[0]) ? $data[0] : null;
        });
    }

    /**
     * Get game details by slug.
     */
    public function getGameBySlug(string $slug): ?array
    {
        $cacheKey = "igdb.game.slug.{$slug}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($slug) {
            $body = "fields name,summary,storyline,cover.url,screenshots.url,first_release_date,genres.name,platforms.name,rating,rating_count,aggregated_rating,aggregated_rating_count,slug,url,websites.url,websites.category; where slug = \"{$slug}\";";

            $data = $this->apiRequest('games', $body);

            return $data && isset($data[0]) ? $data[0] : null;
        });
    }

    /**
     * Get multiple games by IGDB IDs.
     */
    public function getGamesByIds(array $igdbIds): Collection
    {
        if (empty($igdbIds)) {
            return collect();
        }

        $ids = implode(',', $igdbIds);
        $cacheKey = 'igdb.games.'.md5($ids);

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($ids) {
            $body = "fields name,summary,cover.url,first_release_date,genres.name,platforms.name,rating,rating_count,slug,url; where id = ({$ids}); limit 100;";

            $data = $this->apiRequest('games', $body);

            return $data ? collect($data) : collect();
        });
    }

    /**
     * Get popular games.
     */
    public function getPopularGames(int $limit = 20): Collection
    {
        $cacheKey = "igdb.popular.{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit) {
            // Get games with high ratings and rating counts
            $body = "fields name,summary,cover.url,first_release_date,genres.name,platforms.name,rating,rating_count,slug,url; where rating_count > 100 & rating > 75; sort rating_count desc; limit {$limit};";

            $data = $this->apiRequest('games', $body);

            return $data ? collect($data) : collect();
        });
    }

    /**
     * Get recent games.
     */
    public function getRecentGames(int $limit = 20): Collection
    {
        $cacheKey = "igdb.recent.{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($limit) {
            // Get recently released games
            $timestamp = now()->subMonths(3)->timestamp;
            $body = "fields name,summary,cover.url,first_release_date,genres.name,platforms.name,rating,rating_count,slug,url; where first_release_date > {$timestamp} & rating_count > 10; sort first_release_date desc; limit {$limit};";

            $data = $this->apiRequest('games', $body);

            return $data ? collect($data) : collect();
        });
    }

    /**
     * Format cover URL to get proper size.
     */
    public function formatCoverUrl(?string $url, string $size = 'cover_big'): ?string
    {
        if (! $url) {
            return null;
        }

        // IGDB URLs come as //images.igdb.com/...
        // We need to replace the size placeholder and add https
        if (str_starts_with($url, '//')) {
            $url = 'https:'.$url;
        }

        // Replace t_thumb with desired size
        $url = str_replace('t_thumb', "t_{$size}", $url);

        return $url;
    }

    /**
     * Clear IGDB cache.
     */
    public function clearCache(): void
    {
        Cache::forget('igdb.access_token');
    }
}
