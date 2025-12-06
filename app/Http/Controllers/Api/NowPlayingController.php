<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\AzuraCastService;
use App\Services\IcecastService;
use App\Services\ShoutcastService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * API Controller for Now Playing data.
 *
 * Provides high-performance now playing updates using Server-Sent Events (SSE)
 * as recommended by AzuraCast documentation, with polling fallback support.
 *
 * @see https://azuracast.com/docs/developers/now-playing-data/#high-performance-updates
 */
class NowPlayingController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast,
        protected IcecastService $icecast,
        protected ShoutcastService $shoutcast
    ) {}

    /**
     * Get current now playing data as JSON.
     *
     * This is the polling fallback method.
     */
    public function index(): JsonResponse
    {
        try {
            $serverType = config('services.radio.server_type', 'azuracast');

            $data = match ($serverType) {
                'azuracast' => $this->getAzuraCastNowPlaying(),
                'shoutcast' => $this->getShoutcastNowPlaying(),
                'icecast' => $this->getIcecastNowPlaying(),
                default => $this->getAzuraCastNowPlaying(),
            };

            return response()->json([
                'success' => true,
                'data' => $data,
                'server_type' => $serverType,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch now playing data.',
            ], 503);
        }
    }

    /**
     * Get SSE endpoint configuration.
     *
     * Returns the SSE endpoint URL and configuration for the client
     * to establish a direct SSE connection to AzuraCast.
     */
    public function sseConfig(Request $request): JsonResponse
    {
        $sseEnabled = config('services.radio.sse_enabled', true);
        $serverType = config('services.radio.server_type', 'azuracast');
        $pollingInterval = config('services.radio.polling_interval', 15);

        // SSE is primarily supported by AzuraCast
        if ($sseEnabled && $serverType === 'azuracast' && $this->azuraCast->isConfigured()) {
            $baseUrl = rtrim(config('services.azuracast.base_url', ''), '/');
            $stationId = config('services.azuracast.station_id', 1);

            // AzuraCast SSE endpoints
            // Option 1: Station-specific SSE stream
            $sseUrl = "{$baseUrl}/api/live/nowplaying/sse";
            $sseParams = [
                'cf_connect' => json_encode(['subs' => ["station:{$stationId}"]]),
            ];

            // Option 2: For simpler use, the nowplaying endpoint with updates
            $nowPlayingUrl = "{$baseUrl}/api/nowplaying/{$stationId}";

            return response()->json([
                'success' => true,
                'sse_enabled' => true,
                'sse_url' => $sseUrl,
                'sse_params' => $sseParams,
                'nowplaying_url' => $nowPlayingUrl,
                'station_id' => $stationId,
                'polling_interval' => $pollingInterval,
                'fallback_url' => route('api.nowplaying.index'),
            ]);
        }

        // Return polling configuration when SSE is not available
        return response()->json([
            'success' => true,
            'sse_enabled' => false,
            'polling_interval' => $pollingInterval,
            'polling_url' => route('api.nowplaying.index'),
        ]);
    }

    /**
     * Proxy Server-Sent Events from AzuraCast.
     *
     * This endpoint acts as a proxy for AzuraCast's SSE stream,
     * useful when direct access to AzuraCast is not possible.
     *
     * Note: For best performance, clients should connect directly to AzuraCast's
     * SSE endpoint when possible. This proxy is designed for cases where
     * direct access is blocked (e.g., CORS issues, firewall restrictions).
     */
    public function sseProxy(): StreamedResponse
    {
        $sseEnabled = config('services.radio.sse_enabled', true);

        if (! $sseEnabled) {
            abort(404, 'SSE is not enabled');
        }

        return new StreamedResponse(function () {
            // Disable output buffering for SSE
            if (ob_get_level()) {
                ob_end_clean();
            }

            $stationId = config('services.azuracast.station_id', 1);
            $lastData = null;

            // Poll interval - balance between responsiveness and server load
            // AzuraCast caches now-playing for 30 seconds, so polling more frequently
            // than 10 seconds provides little benefit while increasing server load
            $pollInterval = (int) config('services.radio.polling_interval', 15);
            $pollInterval = max(10, min($pollInterval, 30)); // Clamp between 10-30 seconds

            // Set script timeout with buffer for graceful shutdown
            $maxRuntime = 55; // Stay under typical 60-second timeouts
            set_time_limit($maxRuntime + 5);

            // Send initial event
            echo "event: connect\n";
            echo 'data: '.json_encode(['connected' => true])."\n\n";
            flush();

            $startTime = time();

            while (time() - $startTime < $maxRuntime) {
                try {
                    $nowPlaying = $this->azuraCast->getNowPlaying();
                    $data = $nowPlaying->toArray();

                    // Only send if data changed or this is the first message
                    $dataHash = md5(json_encode($data));
                    if ($dataHash !== $lastData) {
                        echo "event: nowplaying\n";
                        echo 'data: '.json_encode([
                            'np' => $data,
                            'station' => ['shortcode' => "station{$stationId}"],
                        ])."\n\n";
                        flush();

                        $lastData = $dataHash;
                    }
                } catch (\Exception $e) {
                    echo "event: error\n";
                    echo 'data: '.json_encode(['error' => 'Failed to fetch data'])."\n\n";
                    flush();
                }

                // Connection still active?
                if (connection_aborted()) {
                    break;
                }

                sleep($pollInterval);
            }

            // Send close event
            echo "event: close\n";
            echo 'data: '.json_encode(['reason' => 'timeout'])."\n\n";
            flush();
        }, 200, [
            'Content-Type' => 'text/event-stream',
            'Cache-Control' => 'no-cache',
            'Connection' => 'keep-alive',
            'X-Accel-Buffering' => 'no', // Nginx
        ]);
    }

    /**
     * Get now playing from AzuraCast.
     */
    protected function getAzuraCastNowPlaying(): array
    {
        $nowPlaying = $this->azuraCast->getNowPlaying();

        return $nowPlaying->toArray();
    }

    /**
     * Get now playing from Shoutcast.
     */
    protected function getShoutcastNowPlaying(): array
    {
        $status = $this->shoutcast->getStatus();
        $nowPlaying = $this->shoutcast->getNowPlaying();

        return [
            'is_online' => $status['is_online'],
            'listeners' => $status['listeners'],
            'current_song' => [
                'id' => md5($nowPlaying['song'] ?? ''),
                'title' => $this->parseSongTitle($nowPlaying['song'] ?? ''),
                'artist' => $this->parseSongArtist($nowPlaying['song'] ?? ''),
                'text' => $nowPlaying['song'] ?? '',
            ],
            'elapsed' => 0,
            'duration' => 0,
            'is_live' => false,
            'next_song' => null,
            'server_type' => 'shoutcast',
        ];
    }

    /**
     * Get now playing from Icecast.
     */
    protected function getIcecastNowPlaying(): array
    {
        $status = $this->icecast->getStatus();

        return [
            'is_online' => $status['is_online'],
            'listeners' => $status['listeners'],
            'current_song' => [
                'id' => md5($status['title'] ?? ''),
                'title' => $this->parseSongTitle($status['title'] ?? ''),
                'artist' => $this->parseSongArtist($status['title'] ?? ''),
                'text' => $status['title'] ?? '',
            ],
            'elapsed' => 0,
            'duration' => 0,
            'is_live' => false,
            'next_song' => null,
            'server_type' => 'icecast',
        ];
    }

    /**
     * Parse song title from "Artist - Title" format.
     */
    protected function parseSongTitle(?string $text): string
    {
        if (empty($text)) {
            return 'Unknown';
        }

        $parts = explode(' - ', $text, 2);

        return trim($parts[1] ?? $parts[0]);
    }

    /**
     * Parse artist from "Artist - Title" format.
     */
    protected function parseSongArtist(?string $text): string
    {
        if (empty($text)) {
            return 'Unknown';
        }

        $parts = explode(' - ', $text, 2);

        return count($parts) > 1 ? trim($parts[0]) : 'Unknown';
    }
}
