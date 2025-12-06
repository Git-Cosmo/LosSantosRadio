<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Service for interacting with Shoutcast radio servers.
 *
 * Shoutcast provides similar functionality to Icecast but with different API endpoints.
 * This service handles streaming status, listener counts, and stream URLs.
 *
 * @see https://wiki.shoutcast.com/wiki/Shoutcast_Server_HTTP_API
 */
class ShoutcastService
{
    protected string $host;

    protected int $port;

    protected string $adminPassword;

    protected bool $ssl;

    protected int $streamId;

    public function __construct()
    {
        $this->host = config('services.shoutcast.host', 'localhost');
        $this->port = (int) config('services.shoutcast.port', 8000);
        $this->adminPassword = config('services.shoutcast.admin_password', '');
        $this->ssl = (bool) config('services.shoutcast.ssl', false);
        $this->streamId = (int) config('services.shoutcast.stream_id', 1);
    }

    /**
     * Check if the service is properly configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->host);
    }

    /**
     * Get the stream URL.
     *
     * Shoutcast v2 uses /stream/{sid}/ for multi-stream support
     */
    public function getStreamUrl(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';

        return "{$protocol}://{$this->host}:{$this->port}/stream/{$this->streamId}/";
    }

    /**
     * Get alternate stream URL (legacy format).
     */
    public function getLegacyStreamUrl(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';

        return "{$protocol}://{$this->host}:{$this->port}/;stream.nsv";
    }

    /**
     * Get current stream status using the statistics endpoint.
     */
    public function getStatus(): array
    {
        $cacheKey = 'shoutcast.status';

        return Cache::remember($cacheKey, 10, function () {
            // Try XML stats first (more detailed), fallback to JSON
            $status = $this->fetchXmlStats();

            if ($status !== null) {
                return $status;
            }

            // Fallback to JSON stats
            $status = $this->fetchJsonStats();

            if ($status !== null) {
                return $status;
            }

            return $this->getDefaultStatus();
        });
    }

    /**
     * Fetch statistics in XML format.
     */
    protected function fetchXmlStats(): ?array
    {
        try {
            $statsUrl = $this->getBaseUrl().'/statistics';
            $params = ['sid' => $this->streamId];

            if (! empty($this->adminPassword)) {
                $params['password'] = $this->adminPassword;
            }

            $response = Http::timeout(5)->get($statsUrl, $params);

            if ($response->successful()) {
                return $this->parseXmlStats($response->body());
            }
        } catch (\Exception $e) {
            Log::debug('Shoutcast XML stats fetch failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Fetch statistics in JSON format (Shoutcast v2+).
     */
    protected function fetchJsonStats(): ?array
    {
        try {
            $statsUrl = $this->getBaseUrl().'/stats';
            $params = ['sid' => $this->streamId, 'json' => 1];

            if (! empty($this->adminPassword)) {
                $params['password'] = $this->adminPassword;
            }

            $response = Http::timeout(5)->get($statsUrl, $params);

            if ($response->successful()) {
                return $this->parseJsonStats($response->json());
            }
        } catch (\Exception $e) {
            Log::debug('Shoutcast JSON stats fetch failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get listener statistics.
     */
    public function getListenerStats(): array
    {
        $status = $this->getStatus();

        return [
            'current' => $status['listeners'],
            'peak' => $status['peak_listeners'],
            'max' => $status['max_listeners'],
            'unique' => $status['unique_listeners'],
        ];
    }

    /**
     * Get now playing information from Shoutcast.
     */
    public function getNowPlaying(): array
    {
        $status = $this->getStatus();

        return [
            'title' => $status['title'],
            'song' => $status['current_song'],
            'genre' => $status['genre'],
            'content_type' => $status['content_type'],
        ];
    }

    /**
     * Check if the stream is online.
     */
    public function isOnline(): bool
    {
        $status = $this->getStatus();

        return $status['is_online'];
    }

    /**
     * Get the base URL for Shoutcast server.
     */
    protected function getBaseUrl(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';

        return "{$protocol}://{$this->host}:{$this->port}";
    }

    /**
     * Parse Shoutcast XML statistics response.
     */
    protected function parseXmlStats(string $xmlContent): ?array
    {
        try {
            $xml = simplexml_load_string($xmlContent);

            if ($xml === false) {
                return null;
            }

            // Handle different XML structures
            $streamStatus = null;
            if (isset($xml->STREAMSTATS)) {
                $streamStatus = $xml->STREAMSTATS->STREAM;
            } elseif (isset($xml->STREAM)) {
                $streamStatus = $xml->STREAM;
            }

            if (! $streamStatus) {
                // Check for single stream format
                if (isset($xml->CURRENTLISTENERS)) {
                    return [
                        'is_online' => ((int) ($xml->STREAMSTATUS ?? 1)) === 1,
                        'listeners' => (int) ($xml->CURRENTLISTENERS ?? 0),
                        'peak_listeners' => (int) ($xml->PEAKLISTENERS ?? 0),
                        'max_listeners' => (int) ($xml->MAXLISTENERS ?? 0),
                        'unique_listeners' => (int) ($xml->UNIQUELISTENERS ?? 0),
                        'bitrate' => (int) ($xml->BITRATE ?? 0),
                        'title' => (string) ($xml->SERVERTITLE ?? ''),
                        'current_song' => (string) ($xml->SONGTITLE ?? ''),
                        'genre' => (string) ($xml->SERVERGENRE ?? ''),
                        'content_type' => (string) ($xml->CONTENT ?? 'audio/mpeg'),
                        'server_url' => (string) ($xml->SERVERURL ?? ''),
                    ];
                }

                return null;
            }

            return [
                'is_online' => ((int) ($streamStatus->STREAMSTATUS ?? 1)) === 1,
                'listeners' => (int) ($streamStatus->CURRENTLISTENERS ?? 0),
                'peak_listeners' => (int) ($streamStatus->PEAKLISTENERS ?? 0),
                'max_listeners' => (int) ($streamStatus->MAXLISTENERS ?? 0),
                'unique_listeners' => (int) ($streamStatus->UNIQUELISTENERS ?? 0),
                'bitrate' => (int) ($streamStatus->BITRATE ?? 0),
                'title' => (string) ($streamStatus->SERVERTITLE ?? ''),
                'current_song' => (string) ($streamStatus->SONGTITLE ?? ''),
                'genre' => (string) ($streamStatus->SERVERGENRE ?? ''),
                'content_type' => (string) ($streamStatus->CONTENT ?? 'audio/mpeg'),
                'server_url' => (string) ($streamStatus->SERVERURL ?? ''),
            ];
        } catch (\Exception $e) {
            Log::warning('Shoutcast XML parsing failed', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Parse Shoutcast JSON statistics response.
     */
    protected function parseJsonStats(?array $data): ?array
    {
        if (! $data) {
            return null;
        }

        try {
            // Shoutcast v2 JSON format
            if (isset($data['streams']) && is_array($data['streams'])) {
                $stream = $data['streams'][0] ?? null;

                if ($stream) {
                    return [
                        'is_online' => ($stream['streamstatus'] ?? 0) === 1,
                        'listeners' => (int) ($stream['currentlisteners'] ?? 0),
                        'peak_listeners' => (int) ($stream['peaklisteners'] ?? 0),
                        'max_listeners' => (int) ($stream['maxlisteners'] ?? 0),
                        'unique_listeners' => (int) ($stream['uniquelisteners'] ?? 0),
                        'bitrate' => (int) ($stream['bitrate'] ?? 0),
                        'title' => (string) ($stream['servertitle'] ?? ''),
                        'current_song' => (string) ($stream['songtitle'] ?? ''),
                        'genre' => (string) ($stream['servergenre'] ?? ''),
                        'content_type' => (string) ($stream['content'] ?? 'audio/mpeg'),
                        'server_url' => (string) ($stream['serverurl'] ?? ''),
                    ];
                }
            }

            // Alternative flat JSON format
            if (isset($data['currentlisteners'])) {
                return [
                    'is_online' => ($data['streamstatus'] ?? 0) === 1,
                    'listeners' => (int) ($data['currentlisteners'] ?? 0),
                    'peak_listeners' => (int) ($data['peaklisteners'] ?? 0),
                    'max_listeners' => (int) ($data['maxlisteners'] ?? 0),
                    'unique_listeners' => (int) ($data['uniquelisteners'] ?? 0),
                    'bitrate' => (int) ($data['bitrate'] ?? 0),
                    'title' => (string) ($data['servertitle'] ?? ''),
                    'current_song' => (string) ($data['songtitle'] ?? ''),
                    'genre' => (string) ($data['servergenre'] ?? ''),
                    'content_type' => (string) ($data['content'] ?? 'audio/mpeg'),
                    'server_url' => (string) ($data['serverurl'] ?? ''),
                ];
            }
        } catch (\Exception $e) {
            Log::warning('Shoutcast JSON parsing failed', ['error' => $e->getMessage()]);
        }

        return null;
    }

    /**
     * Get default status when Shoutcast is unreachable or stream is offline.
     */
    protected function getDefaultStatus(): array
    {
        return [
            'is_online' => false,
            'listeners' => 0,
            'peak_listeners' => 0,
            'max_listeners' => 0,
            'unique_listeners' => 0,
            'bitrate' => 0,
            'title' => null,
            'current_song' => null,
            'genre' => null,
            'content_type' => null,
            'server_url' => null,
        ];
    }

    /**
     * Clear cached status.
     */
    public function clearCache(): void
    {
        Cache::forget('shoutcast.status');
    }
}
