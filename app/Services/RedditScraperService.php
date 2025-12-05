<?php

namespace App\Services;

use App\Models\FreeGame;
use App\Models\Video;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RedditScraperService
{
    protected int $cacheTtl = 1800; // 30 minutes

    protected array $freeGameSubreddits = [
        'GameDeals',
        'FreeGameFindings',
        'freegames',
    ];

    protected array $funnyVideoSubreddits = [
        'funnyvideos',
        'ContagiousLaughter',
    ];

    protected array $streamClipsSubreddits = [
        'LivestreamFail',
        'Twitch',
    ];

    /**
     * Fetch posts from a subreddit.
     */
    public function fetchSubreddit(string $subreddit, string $sort = 'hot', int $limit = 25): Collection
    {
        $cacheKey = "reddit.{$subreddit}.{$sort}.{$limit}";

        return Cache::remember($cacheKey, $this->cacheTtl, function () use ($subreddit, $sort, $limit) {
            try {
                $url = "https://www.reddit.com/r/{$subreddit}/{$sort}.json";

                $response = Http::timeout(30)
                    ->withHeaders([
                        'User-Agent' => 'LosSantosRadio/1.0 (Laravel App)',
                    ])
                    ->get($url, ['limit' => $limit]);

                if ($response->failed()) {
                    Log::warning("Reddit: Failed to fetch r/{$subreddit}", ['status' => $response->status()]);

                    return collect();
                }

                $data = $response->json();
                $posts = $data['data']['children'] ?? [];

                return collect($posts)->map(fn ($post) => $post['data']);
            } catch (\Exception $e) {
                Log::error("Reddit: Error fetching r/{$subreddit}", ['error' => $e->getMessage()]);

                return collect();
            }
        });
    }

    /**
     * Fetch and sync free games from Reddit.
     */
    public function syncFreeGames(): int
    {
        $synced = 0;

        foreach ($this->freeGameSubreddits as $subreddit) {
            $posts = $this->fetchSubreddit($subreddit, 'hot', 50);

            foreach ($posts as $post) {
                // Skip non-link posts
                if (empty($post['url']) || $post['is_self'] ?? false) {
                    continue;
                }

                // Skip if already exists
                if (FreeGame::where('source_id', $post['id'])->exists()) {
                    continue;
                }

                // Try to parse game info from title
                $title = $this->cleanTitle($post['title']);
                $platform = $this->detectPlatform($post['title'].' '.$post['url']);
                $store = $this->detectStore($post['url']);

                // Skip if it doesn't look like a free game
                if (! $this->looksLikeFreeGame($post['title'])) {
                    continue;
                }

                FreeGame::create([
                    'title' => Str::limit($title, 250),
                    'description' => Str::limit($post['selftext'] ?? '', 1000),
                    'platform' => $platform,
                    'store' => $store,
                    'url' => $post['url'],
                    'image_url' => $this->extractImageUrl($post),
                    'source' => 'reddit',
                    'source_id' => $post['id'],
                    'is_active' => true,
                ]);

                $synced++;
            }
        }

        Log::info('Reddit: Synced free games', ['synced' => $synced]);

        return $synced;
    }

    /**
     * Fetch and sync videos from Reddit.
     */
    public function syncVideos(string $category = 'ylyl'): int
    {
        $subreddits = $category === 'ylyl'
            ? $this->funnyVideoSubreddits
            : $this->streamClipsSubreddits;

        $synced = 0;

        foreach ($subreddits as $subreddit) {
            $posts = $this->fetchSubreddit($subreddit, 'hot', 50);

            foreach ($posts as $post) {
                // Skip if already exists
                if (Video::where('source_id', $post['id'])->exists()) {
                    continue;
                }

                // Only process video posts
                $videoUrl = $this->extractVideoUrl($post);
                if (! $videoUrl) {
                    continue;
                }

                $platform = $this->detectVideoPlatform($videoUrl);

                Video::create([
                    'title' => Str::limit($this->cleanTitle($post['title']), 250),
                    'description' => Str::limit($post['selftext'] ?? '', 1000),
                    'video_url' => $videoUrl,
                    'embed_url' => $this->generateEmbedUrl($videoUrl, $platform),
                    'thumbnail_url' => $this->extractImageUrl($post),
                    'category' => $category,
                    'platform' => $platform,
                    'source' => 'reddit',
                    'source_id' => $post['id'],
                    'author' => $post['author'] ?? 'Unknown',
                    'upvotes' => (int) ($post['ups'] ?? 0),
                    'is_active' => true,
                    'posted_at' => isset($post['created_utc'])
                        ? \Carbon\Carbon::createFromTimestamp($post['created_utc'])
                        : now(),
                ]);

                $synced++;
            }
        }

        Log::info("Reddit: Synced {$category} videos", ['synced' => $synced]);

        return $synced;
    }

    /**
     * Clean up a title string.
     */
    protected function cleanTitle(string $title): string
    {
        // Remove common Reddit title prefixes like [FREE], [STEAM], etc.
        $title = preg_replace('/^\[.*?\]\s*/', '', $title);

        // Remove "(free)" and similar
        $title = preg_replace('/\(free\)/i', '', $title);

        return trim($title);
    }

    /**
     * Check if a title looks like a free game post.
     */
    protected function looksLikeFreeGame(string $title): bool
    {
        $keywords = ['free', '100%', 'giveaway', 'claim', 'grab'];
        $titleLower = strtolower($title);

        foreach ($keywords as $keyword) {
            if (str_contains($titleLower, $keyword)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect the platform from text.
     */
    protected function detectPlatform(string $text): ?string
    {
        $textLower = strtolower($text);

        $platforms = [
            'PC' => ['steam', 'epic', 'gog', 'humble', 'itch.io', 'pc'],
            'PlayStation' => ['playstation', 'psn', 'ps4', 'ps5'],
            'Xbox' => ['xbox', 'microsoft store'],
            'Nintendo' => ['nintendo', 'switch', 'eshop'],
            'Mobile' => ['ios', 'android', 'mobile'],
        ];

        foreach ($platforms as $platform => $keywords) {
            foreach ($keywords as $keyword) {
                if (str_contains($textLower, $keyword)) {
                    return $platform;
                }
            }
        }

        return null;
    }

    /**
     * Detect the store from URL.
     */
    protected function detectStore(string $url): ?string
    {
        $stores = [
            'Steam' => 'store.steampowered.com',
            'Epic Games' => 'epicgames.com',
            'GOG' => 'gog.com',
            'Humble' => 'humblebundle.com',
            'itch.io' => 'itch.io',
            'Ubisoft' => 'store.ubi.com',
            'EA' => 'origin.com',
            'PlayStation' => 'store.playstation.com',
            'Xbox' => 'xbox.com',
        ];

        foreach ($stores as $store => $domain) {
            if (str_contains($url, $domain)) {
                return $store;
            }
        }

        return null;
    }

    /**
     * Extract image URL from Reddit post.
     */
    protected function extractImageUrl(array $post): ?string
    {
        // Try thumbnail first
        if (! empty($post['thumbnail']) && filter_var($post['thumbnail'], FILTER_VALIDATE_URL)) {
            return $post['thumbnail'];
        }

        // Try preview images
        if (! empty($post['preview']['images'][0]['source']['url'])) {
            return html_entity_decode($post['preview']['images'][0]['source']['url']);
        }

        return null;
    }

    /**
     * Extract video URL from Reddit post.
     */
    protected function extractVideoUrl(array $post): ?string
    {
        // Direct video URL
        if (! empty($post['url'])) {
            $url = $post['url'];

            // Check if it's a video link
            if ($this->isVideoUrl($url)) {
                return $url;
            }
        }

        // Reddit hosted video
        if (! empty($post['media']['reddit_video']['fallback_url'])) {
            return $post['media']['reddit_video']['fallback_url'];
        }

        // Secure media
        if (! empty($post['secure_media']['reddit_video']['fallback_url'])) {
            return $post['secure_media']['reddit_video']['fallback_url'];
        }

        return null;
    }

    /**
     * Check if URL is a video URL.
     */
    protected function isVideoUrl(string $url): bool
    {
        $videoPatterns = [
            'youtube.com/watch',
            'youtu.be/',
            'twitch.tv/videos/',
            'clips.twitch.tv/',
            'kick.com/',
            'v.redd.it/',
            'streamable.com/',
            '.mp4',
            '.webm',
        ];

        foreach ($videoPatterns as $pattern) {
            if (str_contains($url, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect video platform from URL.
     */
    protected function detectVideoPlatform(string $url): string
    {
        if (str_contains($url, 'youtube.com') || str_contains($url, 'youtu.be')) {
            return 'youtube';
        }

        if (str_contains($url, 'twitch.tv') || str_contains($url, 'clips.twitch.tv')) {
            return 'twitch';
        }

        if (str_contains($url, 'kick.com')) {
            return 'kick';
        }

        if (str_contains($url, 'redd.it') || str_contains($url, 'reddit.com')) {
            return 'reddit';
        }

        return 'other';
    }

    /**
     * Generate embed URL for video.
     */
    protected function generateEmbedUrl(string $url, string $platform): ?string
    {
        return match ($platform) {
            'youtube' => $this->getYoutubeEmbedUrl($url),
            'twitch' => $this->getTwitchEmbedUrl($url),
            default => null,
        };
    }

    /**
     * Get YouTube embed URL.
     */
    protected function getYoutubeEmbedUrl(string $url): ?string
    {
        if (preg_match('/(?:youtube\.com\/watch\?v=|youtu\.be\/)([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://www.youtube.com/embed/'.$matches[1];
        }

        return null;
    }

    /**
     * Get Twitch embed URL.
     */
    protected function getTwitchEmbedUrl(string $url): ?string
    {
        $appUrl = config('app.url', 'localhost');
        $parent = parse_url($appUrl, PHP_URL_HOST) ?: 'localhost';

        if (preg_match('/twitch\.tv\/videos\/(\d+)/', $url, $matches)) {
            return 'https://player.twitch.tv/?video='.$matches[1].'&parent='.$parent;
        }

        if (preg_match('/clips\.twitch\.tv\/([a-zA-Z0-9_-]+)/', $url, $matches)) {
            return 'https://clips.twitch.tv/embed?clip='.$matches[1].'&parent='.$parent;
        }

        return null;
    }

    /**
     * Clear Reddit cache.
     */
    public function clearCache(): void
    {
        foreach ($this->freeGameSubreddits as $subreddit) {
            Cache::forget("reddit.{$subreddit}.hot.50");
        }

        foreach (array_merge($this->funnyVideoSubreddits, $this->streamClipsSubreddits) as $subreddit) {
            Cache::forget("reddit.{$subreddit}.hot.50");
        }
    }
}
