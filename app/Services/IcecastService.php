<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class IcecastService
{
    protected string $host;

    protected int $port;

    protected string $mount;

    protected string $adminUser;

    protected string $adminPassword;

    protected bool $ssl;

    public function __construct()
    {
        $this->host = config('services.icecast.host', 'localhost');
        $this->port = (int) config('services.icecast.port', 8000);
        $this->mount = config('services.icecast.mount', '/stream');
        $this->adminUser = config('services.icecast.admin_user', 'admin');
        $this->adminPassword = config('services.icecast.admin_password', '');
        $this->ssl = (bool) config('services.icecast.ssl', false);
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
     */
    public function getStreamUrl(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';

        return "{$protocol}://{$this->host}:{$this->port}{$this->mount}";
    }

    /**
     * Get current stream status.
     */
    public function getStatus(): array
    {
        $cacheKey = 'icecast.status';

        return Cache::remember($cacheKey, 10, function () {
            try {
                $statusUrl = $this->getBaseUrl().'/status-json.xsl';

                $response = Http::timeout(5)->get($statusUrl);

                if ($response->successful()) {
                    $data = $response->json();

                    return $this->parseStatus($data);
                }

                return $this->getDefaultStatus();
            } catch (\Exception $e) {
                Log::warning('Icecast status check failed', [
                    'error' => $e->getMessage(),
                ]);

                return $this->getDefaultStatus();
            }
        });
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
     * Get the base URL for Icecast server.
     */
    protected function getBaseUrl(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';

        return "{$protocol}://{$this->host}:{$this->port}";
    }

    /**
     * Parse Icecast status response.
     */
    protected function parseStatus(array $data): array
    {
        $source = null;

        // Find the source for our mount point
        if (isset($data['icestats']['source'])) {
            $sources = $data['icestats']['source'];

            // Handle both single source and array of sources
            if (isset($sources['listenurl'])) {
                $sources = [$sources];
            }

            foreach ($sources as $src) {
                if (str_contains($src['listenurl'] ?? '', $this->mount)) {
                    $source = $src;
                    break;
                }
            }
        }

        if ($source) {
            return [
                'is_online' => true,
                'listeners' => (int) ($source['listeners'] ?? 0),
                'peak_listeners' => (int) ($source['listener_peak'] ?? 0),
                'bitrate' => (int) ($source['bitrate'] ?? 0),
                'title' => $source['title'] ?? null,
                'description' => $source['server_description'] ?? null,
                'genre' => $source['genre'] ?? null,
                'audio_info' => $source['audio_info'] ?? null,
            ];
        }

        return $this->getDefaultStatus();
    }

    /**
     * Get default status when Icecast is unreachable or stream is offline.
     */
    protected function getDefaultStatus(): array
    {
        return [
            'is_online' => false,
            'listeners' => 0,
            'peak_listeners' => 0,
            'bitrate' => 0,
            'title' => null,
            'description' => null,
            'genre' => null,
            'audio_info' => null,
        ];
    }

    /**
     * Clear cached status.
     */
    public function clearCache(): void
    {
        Cache::forget('icecast.status');
    }
}
