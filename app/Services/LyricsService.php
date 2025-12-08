<?php

namespace App\Services;

use App\Models\Lyric;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Lyrics Service
 * 
 * Handles lyrics retrieval and matching for songs from AzuraCast.
 * Supports multiple lyrics providers with fallback.
 */
class LyricsService
{
    protected CacheService $cacheService;

    public function __construct(CacheService $cacheService)
    {
        $this->cacheService = $cacheService;
    }

    /**
     * Get lyrics for a song
     */
    public function getLyrics(string $songId, string $title, string $artist): ?Lyric
    {
        // Check database first
        $lyric = Lyric::where('song_id', $songId)->first();

        if ($lyric && $lyric->hasLyrics()) {
            $lyric->incrementViews();
            return $lyric;
        }

        // Try to fetch from API
        $fetchedLyrics = $this->fetchLyricsFromApi($title, $artist);

        if ($fetchedLyrics) {
            $lyric = $this->storeLyrics($songId, $title, $artist, $fetchedLyrics);
            return $lyric;
        }

        // Store empty record to avoid repeated API calls
        if (!$lyric) {
            Lyric::create([
                'song_id' => $songId,
                'title' => $title,
                'artist' => $artist,
                'lyrics' => null,
            ]);
        }

        return null;
    }

    /**
     * Fetch lyrics from external API
     * This is a placeholder - integrate with actual lyrics API (Genius, Musixmatch, etc.)
     */
    protected function fetchLyricsFromApi(string $title, string $artist): ?array
    {
        // Try Genius API first (placeholder)
        $geniusLyrics = $this->fetchFromGenius($title, $artist);
        if ($geniusLyrics) {
            return $geniusLyrics;
        }

        // Try Musixmatch API (placeholder)
        // $musixmatchLyrics = $this->fetchFromMusixmatch($title, $artist);
        // if ($musixmatchLyrics) {
        //     return $musixmatchLyrics;
        // }

        return null;
    }

    /**
     * Fetch lyrics from Genius API
     * Placeholder implementation - requires Genius API token
     */
    protected function fetchFromGenius(string $title, string $artist): ?array
    {
        $apiToken = config('services.genius.api_token');

        if (!$apiToken) {
            Log::debug('Genius API token not configured');
            return null;
        }

        try {
            // Search for the song
            $searchResponse = Http::withHeaders([
                'Authorization' => "Bearer {$apiToken}",
            ])->get('https://api.genius.com/search', [
                'q' => "{$artist} {$title}",
            ]);

            if (!$searchResponse->successful()) {
                return null;
            }

            $searchData = $searchResponse->json();
            $hits = $searchData['response']['hits'] ?? [];

            if (empty($hits)) {
                return null;
            }

            $song = $hits[0]['result'] ?? null;

            if (!$song) {
                return null;
            }

            // Note: Genius API doesn't provide lyrics directly
            // You need to scrape from the song URL or use a lyrics scraping library
            // For now, return null to indicate lyrics not available via API
            // Implement actual scraping or use a lyrics API that provides content

            return null; // Returning null until actual lyrics scraping is implemented
        } catch (\Exception $e) {
            Log::error('Failed to fetch lyrics from Genius', [
                'title' => $title,
                'artist' => $artist,
                'error' => $e->getMessage(),
            ]);

            return null;
        }
    }

    /**
     * Store lyrics in database
     */
    protected function storeLyrics(string $songId, string $title, string $artist, array $data): Lyric
    {
        return Lyric::updateOrCreate(
            ['song_id' => $songId],
            [
                'title' => $title,
                'artist' => $artist,
                'lyrics' => $data['lyrics'] ?? null,
                'source' => $data['source'] ?? null,
                'source_url' => $data['source_url'] ?? null,
                'is_synced' => $data['is_synced'] ?? false,
                'synced_lyrics' => $data['synced_lyrics'] ?? null,
                'views_count' => 1,
                'last_viewed_at' => now(),
            ]
        );
    }

    /**
     * Check if user can view lyrics
     */
    public function canViewLyrics(?int $userId, string $sessionId): array
    {
        // Registered users have unlimited access
        if ($userId) {
            return [
                'can_view' => true,
                'reason' => 'registered_user',
            ];
        }

        // Check if guest has unlocked lyrics
        if ($this->cacheService->hasGuestUnlockedLyrics($sessionId)) {
            return [
                'can_view' => true,
                'reason' => 'unlocked',
            ];
        }

        // Check guest limit
        $viewCount = $this->cacheService->getGuestLyricsViewCount($sessionId);

        if ($viewCount < 4) {
            return [
                'can_view' => true,
                'reason' => 'within_limit',
                'remaining' => 4 - $viewCount,
            ];
        }

        return [
            'can_view' => false,
            'reason' => 'limit_reached',
            'required_action' => 'watch_or_signup',
        ];
    }

    /**
     * Track lyrics view for guest
     */
    public function trackGuestView(string $sessionId, string $songId): void
    {
        $this->cacheService->trackGuestLyricsView($sessionId, $songId);
    }

    /**
     * Unlock lyrics for guest (after watching ad or time requirement)
     */
    public function unlockForGuest(string $sessionId): void
    {
        $this->cacheService->trackGuestUnlockTime($sessionId, time());
    }

    /**
     * Get popular lyrics
     */
    public function getPopularLyrics(int $limit = 10)
    {
        return Lyric::where('lyrics', '!=', null)
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }

    /**
     * Search lyrics
     */
    public function searchLyrics(string $query, int $limit = 20)
    {
        return Lyric::where('lyrics', '!=', null)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('artist', 'like', "%{$query}%")
                    ->orWhere('lyrics', 'like', "%{$query}%");
            })
            ->orderBy('views_count', 'desc')
            ->limit($limit)
            ->get();
    }
}
