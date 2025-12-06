<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Services\AzuraCastService;
use App\Services\IcecastService;
use App\Services\ShoutcastService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Admin controller for managing radio server settings.
 *
 * Handles configuration for AzuraCast, Shoutcast, and Icecast servers,
 * including server type selection, connection testing, and now playing settings.
 */
class RadioServerController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast,
        protected IcecastService $icecast,
        protected ShoutcastService $shoutcast
    ) {}

    /**
     * Display the radio server settings page.
     */
    public function index(): View
    {
        // Get current configuration
        $serverType = config('services.radio.server_type', 'azuracast');
        $nowPlayingMethod = config('services.radio.now_playing_method', 'sse');
        $pollingInterval = config('services.radio.polling_interval', 15);
        $sseEnabled = config('services.radio.sse_enabled', true);

        // Get connection status for each server type
        $connectionStatus = [
            'azuracast' => $this->testAzuraCastConnection(),
            'icecast' => $this->testIcecastConnection(),
            'shoutcast' => $this->testShoutcastConnection(),
        ];

        // Get saved settings from database
        $settings = [
            'radio_server_type' => Setting::get('radio_server_type', $serverType),
            'radio_now_playing_method' => Setting::get('radio_now_playing_method', $nowPlayingMethod),
            'radio_polling_interval' => Setting::get('radio_polling_interval', $pollingInterval),
            'radio_sse_enabled' => Setting::get('radio_sse_enabled', $sseEnabled),

            // AzuraCast settings
            'azuracast_api_url' => Setting::get('azuracast_api_url', config('services.azuracast.api_url')),
            'azuracast_station_id' => Setting::get('azuracast_station_id', config('services.azuracast.station_id')),
            'azuracast_api_key' => Setting::get('azuracast_api_key') ? '••••••••' : '',

            // Icecast settings
            'icecast_host' => Setting::get('icecast_host', config('services.icecast.host')),
            'icecast_port' => Setting::get('icecast_port', config('services.icecast.port')),
            'icecast_mount' => Setting::get('icecast_mount', config('services.icecast.mount')),
            'icecast_ssl' => Setting::get('icecast_ssl', config('services.icecast.ssl')),

            // Shoutcast settings
            'shoutcast_host' => Setting::get('shoutcast_host', config('services.shoutcast.host')),
            'shoutcast_port' => Setting::get('shoutcast_port', config('services.shoutcast.port')),
            'shoutcast_stream_id' => Setting::get('shoutcast_stream_id', config('services.shoutcast.stream_id')),
            'shoutcast_ssl' => Setting::get('shoutcast_ssl', config('services.shoutcast.ssl')),
        ];

        return view('admin.radio.index', [
            'settings' => $settings,
            'connectionStatus' => $connectionStatus,
            'serverTypes' => [
                'azuracast' => 'AzuraCast',
                'shoutcast' => 'Shoutcast',
                'icecast' => 'Icecast',
            ],
            'nowPlayingMethods' => [
                'sse' => 'Server-Sent Events (Recommended)',
                'polling' => 'Polling',
            ],
        ]);
    }

    /**
     * Update radio server settings.
     */
    public function update(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'radio_server_type' => 'required|in:azuracast,shoutcast,icecast',
            'radio_now_playing_method' => 'required|in:sse,polling',
            'radio_polling_interval' => 'required|integer|min:5|max:300',
            'radio_sse_enabled' => 'nullable|boolean',

            // AzuraCast settings
            'azuracast_api_url' => 'nullable|url',
            'azuracast_station_id' => 'nullable|string|max:50',
            'azuracast_api_key' => 'nullable|string|max:255',

            // Icecast settings
            'icecast_host' => 'nullable|string|max:255',
            'icecast_port' => 'nullable|integer|min:1|max:65535',
            'icecast_mount' => 'nullable|string|max:100',
            'icecast_ssl' => 'nullable|boolean',

            // Shoutcast settings
            'shoutcast_host' => 'nullable|string|max:255',
            'shoutcast_port' => 'nullable|integer|min:1|max:65535',
            'shoutcast_stream_id' => 'nullable|integer|min:1|max:999',
            'shoutcast_ssl' => 'nullable|boolean',
        ]);

        // Save general radio settings
        Setting::set('radio_server_type', $validated['radio_server_type']);
        Setting::set('radio_now_playing_method', $validated['radio_now_playing_method']);
        Setting::set('radio_polling_interval', (int) $validated['radio_polling_interval']);
        Setting::set('radio_sse_enabled', (bool) ($validated['radio_sse_enabled'] ?? false));

        // Save AzuraCast settings
        if (! empty($validated['azuracast_api_url'])) {
            Setting::set('azuracast_api_url', $validated['azuracast_api_url']);
        }
        if (! empty($validated['azuracast_station_id'])) {
            Setting::set('azuracast_station_id', $validated['azuracast_station_id']);
        }
        // Only update API key if a new one was provided
        // Check that the field is not empty and doesn't consist entirely of bullet characters (masked value)
        $apiKey = $validated['azuracast_api_key'] ?? '';
        if (! empty($apiKey) && preg_match('/[^•]/', $apiKey)) {
            Setting::set('azuracast_api_key', $apiKey);
        }

        // Save Icecast settings
        if (! empty($validated['icecast_host'])) {
            Setting::set('icecast_host', $validated['icecast_host']);
        }
        if (! empty($validated['icecast_port'])) {
            Setting::set('icecast_port', (int) $validated['icecast_port']);
        }
        if (! empty($validated['icecast_mount'])) {
            Setting::set('icecast_mount', $validated['icecast_mount']);
        }
        Setting::set('icecast_ssl', (bool) ($validated['icecast_ssl'] ?? false));

        // Save Shoutcast settings
        if (! empty($validated['shoutcast_host'])) {
            Setting::set('shoutcast_host', $validated['shoutcast_host']);
        }
        if (! empty($validated['shoutcast_port'])) {
            Setting::set('shoutcast_port', (int) $validated['shoutcast_port']);
        }
        if (! empty($validated['shoutcast_stream_id'])) {
            Setting::set('shoutcast_stream_id', (int) $validated['shoutcast_stream_id']);
        }
        Setting::set('shoutcast_ssl', (bool) ($validated['shoutcast_ssl'] ?? false));

        // Log activity
        activity()
            ->causedBy(auth()->user())
            ->withProperties(['settings' => array_keys($validated)])
            ->log('Updated radio server settings');

        return redirect()->route('admin.radio.index')
            ->with('success', 'Radio server settings updated successfully.');
    }

    /**
     * Test connection to the specified server type.
     */
    public function testConnection(Request $request): RedirectResponse
    {
        $serverType = $request->input('server_type', 'azuracast');

        $status = match ($serverType) {
            'azuracast' => $this->testAzuraCastConnection(),
            'icecast' => $this->testIcecastConnection(),
            'shoutcast' => $this->testShoutcastConnection(),
            default => ['success' => false, 'message' => 'Unknown server type'],
        };

        if ($status['success']) {
            return back()->with('success', "Successfully connected to {$serverType}: {$status['message']}");
        }

        return back()->with('error', "Failed to connect to {$serverType}: {$status['message']}");
    }

    /**
     * Test AzuraCast connection.
     */
    protected function testAzuraCastConnection(): array
    {
        try {
            if (! $this->azuraCast->isConfigured()) {
                return ['success' => false, 'message' => 'Not configured'];
            }

            $station = $this->azuraCast->getStation();

            return [
                'success' => true,
                'message' => "Connected to station: {$station->name}",
                'data' => [
                    'station_name' => $station->name,
                    'requests_enabled' => $station->requestsEnabled,
                ],
            ];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Test Icecast connection.
     */
    protected function testIcecastConnection(): array
    {
        try {
            if (! $this->icecast->isConfigured()) {
                return ['success' => false, 'message' => 'Not configured'];
            }

            $status = $this->icecast->getStatus();

            if ($status['is_online']) {
                return [
                    'success' => true,
                    'message' => "Stream online with {$status['listeners']} listeners",
                    'data' => $status,
                ];
            }

            return ['success' => false, 'message' => 'Stream is offline or unreachable'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Test Shoutcast connection.
     */
    protected function testShoutcastConnection(): array
    {
        try {
            if (! $this->shoutcast->isConfigured()) {
                return ['success' => false, 'message' => 'Not configured'];
            }

            $status = $this->shoutcast->getStatus();

            if ($status['is_online']) {
                return [
                    'success' => true,
                    'message' => "Stream online with {$status['listeners']} listeners",
                    'data' => $status,
                ];
            }

            return ['success' => false, 'message' => 'Stream is offline or unreachable'];
        } catch (\Exception $e) {
            return ['success' => false, 'message' => $e->getMessage()];
        }
    }

    /**
     * Clear all radio-related caches.
     */
    public function clearCache(): RedirectResponse
    {
        $this->icecast->clearCache();
        $this->shoutcast->clearCache();

        // Clear any other radio-related caches
        \Illuminate\Support\Facades\Cache::forget('azuracast.now_playing');
        \Illuminate\Support\Facades\Cache::forget('azuracast.history');
        \Illuminate\Support\Facades\Cache::forget('azuracast.station');

        return back()->with('success', 'Radio caches cleared successfully.');
    }
}
