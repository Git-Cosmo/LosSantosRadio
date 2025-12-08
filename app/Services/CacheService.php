<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Redis as RedisFacade;

/**
 * Universal Cache Service
 * 
 * Centralized cache management with smart DRY patterns and namespace organization.
 * All cache operations should go through this service for consistency and reliability.
 */
class CacheService
{
    /**
     * Cache namespace prefixes for organized key management
     */
    const NAMESPACE_RADIO = 'radio';
    const NAMESPACE_GAMES = 'games';
    const NAMESPACE_LYRICS = 'lyrics';
    const NAMESPACE_USER = 'user';
    const NAMESPACE_CONTENT = 'content';
    const NAMESPACE_SESSION = 'session';

    /**
     * Default TTL values in seconds
     */
    const TTL_REALTIME = 30;        // 30 seconds for real-time data (now playing, etc.)
    const TTL_SHORT = 300;          // 5 minutes for frequently changing data
    const TTL_MEDIUM = 3600;        // 1 hour for moderately stable data
    const TTL_LONG = 43200;         // 12 hours for stable data
    const TTL_VERY_LONG = 86400;    // 24 hours for very stable data

    /**
     * Check if Redis is available
     */
    public function isRedisAvailable(): bool
    {
        try {
            return config('cache.default') === 'redis' && RedisFacade::connection()->ping();
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Get a cache key with namespace prefix
     */
    public function key(string $namespace, string $key): string
    {
        return "{$namespace}/{$key}";
    }

    /**
     * Store a value in cache
     */
    public function put(string $namespace, string $key, mixed $value, ?int $ttl = null): bool
    {
        $cacheKey = $this->key($namespace, $key);
        $ttl = $ttl ?? self::TTL_MEDIUM;

        return Cache::put($cacheKey, $value, $ttl);
    }

    /**
     * Get a value from cache
     */
    public function get(string $namespace, string $key, mixed $default = null): mixed
    {
        $cacheKey = $this->key($namespace, $key);
        return Cache::get($cacheKey, $default);
    }

    /**
     * Remember a value in cache (get or store)
     */
    public function remember(string $namespace, string $key, int $ttl, callable $callback): mixed
    {
        $cacheKey = $this->key($namespace, $key);
        return Cache::remember($cacheKey, $ttl, $callback);
    }

    /**
     * Remember a value forever in cache
     */
    public function rememberForever(string $namespace, string $key, callable $callback): mixed
    {
        $cacheKey = $this->key($namespace, $key);
        return Cache::rememberForever($cacheKey, $callback);
    }

    /**
     * Check if a key exists in cache
     */
    public function has(string $namespace, string $key): bool
    {
        $cacheKey = $this->key($namespace, $key);
        return Cache::has($cacheKey);
    }

    /**
     * Forget a specific cache key
     */
    public function forget(string $namespace, string $key): bool
    {
        $cacheKey = $this->key($namespace, $key);
        return Cache::forget($cacheKey);
    }

    /**
     * Forget all keys in a namespace using tags (Redis/Memcached only)
     * Falls back to manual deletion for other drivers
     */
    public function forgetNamespace(string $namespace): bool
    {
        if ($this->isRedisAvailable()) {
            try {
                Cache::tags([$namespace])->flush();
                return true;
            } catch (\Exception $e) {
                // Fallback to manual deletion
            }
        }

        // For file/database cache, we need to manually track and delete keys
        // This is a limitation of non-taggable cache stores
        return false;
    }

    /**
     * Get with tags (Redis/Memcached only)
     */
    public function getTagged(array $tags, string $key, mixed $default = null): mixed
    {
        if ($this->isRedisAvailable()) {
            return Cache::tags($tags)->get($key, $default);
        }

        return $this->get($tags[0] ?? 'default', $key, $default);
    }

    /**
     * Put with tags (Redis/Memcached only)
     */
    public function putTagged(array $tags, string $key, mixed $value, ?int $ttl = null): bool
    {
        $ttl = $ttl ?? self::TTL_MEDIUM;

        if ($this->isRedisAvailable()) {
            return Cache::tags($tags)->put($key, $value, $ttl);
        }

        return $this->put($tags[0] ?? 'default', $key, $value, $ttl);
    }

    /**
     * Radio-specific: Cache now playing data
     */
    public function cacheNowPlaying(int $stationId, array $data): bool
    {
        return $this->put(self::NAMESPACE_RADIO, "nowplaying.{$stationId}", $data, self::TTL_REALTIME);
    }

    /**
     * Radio-specific: Get cached now playing data
     */
    public function getNowPlaying(int $stationId): ?array
    {
        return $this->get(self::NAMESPACE_RADIO, "nowplaying.{$stationId}");
    }

    /**
     * Radio-specific: Invalidate now playing cache
     */
    public function invalidateNowPlaying(int $stationId): bool
    {
        return $this->forget(self::NAMESPACE_RADIO, "nowplaying.{$stationId}");
    }

    /**
     * Lyrics-specific: Cache lyrics data
     */
    public function cacheLyrics(string $songId, array $lyrics): bool
    {
        return $this->put(self::NAMESPACE_LYRICS, "song.{$songId}", $lyrics, self::TTL_VERY_LONG);
    }

    /**
     * Lyrics-specific: Get cached lyrics
     */
    public function getLyrics(string $songId): ?array
    {
        return $this->get(self::NAMESPACE_LYRICS, "song.{$songId}");
    }

    /**
     * Session-specific: Track guest lyrics views
     */
    public function trackGuestLyricsView(string $sessionId, string $songId): bool
    {
        $key = "guest_lyrics_views.{$sessionId}";
        $views = $this->get(self::NAMESPACE_SESSION, $key, []);
        
        if (!in_array($songId, $views)) {
            $views[] = $songId;
            return $this->put(self::NAMESPACE_SESSION, $key, $views, self::TTL_VERY_LONG);
        }

        return true;
    }

    /**
     * Session-specific: Get guest lyrics view count
     */
    public function getGuestLyricsViewCount(string $sessionId): int
    {
        $key = "guest_lyrics_views.{$sessionId}";
        $views = $this->get(self::NAMESPACE_SESSION, $key, []);
        return count($views);
    }

    /**
     * Session-specific: Track guest unlock time
     */
    public function trackGuestUnlockTime(string $sessionId, int $timestamp): bool
    {
        $key = "guest_unlock_time.{$sessionId}";
        return $this->put(self::NAMESPACE_SESSION, $key, $timestamp, self::TTL_VERY_LONG);
    }

    /**
     * Guest lyrics unlock duration in seconds (10 minutes)
     */
    const GUEST_LYRICS_UNLOCK_DURATION = 600;

    /**
     * Session-specific: Check if guest has unlocked lyrics
     */
    public function hasGuestUnlockedLyrics(string $sessionId): bool
    {
        $key = "guest_unlock_time.{$sessionId}";
        $unlockTime = $this->get(self::NAMESPACE_SESSION, $key);
        
        if (!$unlockTime) {
            return false;
        }

        // Check if unlock is still valid
        return (time() - $unlockTime) < self::GUEST_LYRICS_UNLOCK_DURATION;
    }

    /**
     * Flush all cache
     */
    public function flush(): bool
    {
        return Cache::flush();
    }
}
