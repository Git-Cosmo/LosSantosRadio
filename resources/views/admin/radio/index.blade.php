<x-admin.layouts.app title="Radio Server Settings">
    <div class="admin-header">
        <h1><i class="fas fa-broadcast-tower" style="margin-right: 0.5rem;"></i> Radio Server Settings</h1>
        <div class="header-actions">
            <form action="{{ route('admin.radio.clear-cache') }}" method="POST" style="display: inline;">
                @csrf
                <button type="submit" class="btn btn-secondary">
                    <i class="fas fa-sync"></i> Clear Cache
                </button>
            </form>
        </div>
    </div>

    <!-- Connection Status Overview -->
    <div class="stats-grid" style="margin-bottom: 2rem;">
        @foreach($connectionStatus as $type => $status)
            <div class="stat-card">
                <div style="display: flex; justify-content: space-between; align-items: center;">
                    <div>
                        <div class="stat-label">{{ $serverTypes[$type] }}</div>
                        <div class="stat-value" style="font-size: 1rem;">
                            @if($status['success'])
                                <span style="color: var(--color-success);">
                                    <i class="fas fa-check-circle"></i> Connected
                                </span>
                            @else
                                <span style="color: var(--color-text-muted);">
                                    <i class="fas fa-times-circle"></i> {{ $status['message'] }}
                                </span>
                            @endif
                        </div>
                    </div>
                    <form action="{{ route('admin.radio.test') }}" method="POST" style="margin: 0;">
                        @csrf
                        <input type="hidden" name="server_type" value="{{ $type }}">
                        <button type="submit" class="btn btn-sm btn-secondary" title="Test Connection">
                            <i class="fas fa-plug"></i>
                        </button>
                    </form>
                </div>
            </div>
        @endforeach
    </div>

    <form action="{{ route('admin.radio.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="grid" style="grid-template-columns: 1fr 1fr; gap: 1.5rem;">
            <!-- General Settings Card -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-cog" style="margin-right: 0.5rem;"></i> General Settings</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Active Server Type</label>
                        <select name="radio_server_type" class="form-select" id="serverTypeSelect">
                            @foreach($serverTypes as $value => $label)
                                <option value="{{ $value }}" {{ $settings['radio_server_type'] === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: var(--color-text-muted);">Select the streaming server to use for now playing data.</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Now Playing Update Method</label>
                        <select name="radio_now_playing_method" class="form-select">
                            @foreach($nowPlayingMethods as $value => $label)
                                <option value="{{ $value }}" {{ $settings['radio_now_playing_method'] === $value ? 'selected' : '' }}>
                                    {{ $label }}
                                </option>
                            @endforeach
                        </select>
                        <small style="color: var(--color-text-muted);">
                            SSE provides real-time updates with lower server load (AzuraCast only).
                        </small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Polling Interval (seconds)</label>
                        <input type="number" name="radio_polling_interval" class="form-input"
                               value="{{ $settings['radio_polling_interval'] }}" min="5" max="300">
                        <small style="color: var(--color-text-muted);">How often to check for now playing updates (polling mode only).</small>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="radio_sse_enabled" id="sseEnabled" class="form-check-input" value="1"
                                   {{ $settings['radio_sse_enabled'] ? 'checked' : '' }}>
                            <label for="sseEnabled" class="form-label" style="margin-bottom: 0;">Enable Server-Sent Events</label>
                        </div>
                        <small style="color: var(--color-text-muted);">
                            Use <a href="https://azuracast.com/docs/developers/now-playing-data/#high-performance-updates" target="_blank" rel="noopener">
                            high-performance updates</a> as recommended by AzuraCast.
                        </small>
                    </div>
                </div>
            </div>

            <!-- AzuraCast Settings Card -->
            <div class="card server-config" data-server="azuracast">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-broadcast-tower" style="margin-right: 0.5rem; color: #3b82f6;"></i> AzuraCast Settings</h3>
                    @if($connectionStatus['azuracast']['success'])
                        <span class="badge badge-success">Connected</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">API URL</label>
                        <input type="url" name="azuracast_api_url" class="form-input"
                               value="{{ $settings['azuracast_api_url'] }}"
                               placeholder="https://your-azuracast-server.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Station ID</label>
                        <input type="text" name="azuracast_station_id" class="form-input"
                               value="{{ $settings['azuracast_station_id'] }}"
                               placeholder="1 or station_shortcode">
                    </div>

                    <div class="form-group">
                        <label class="form-label">API Key</label>
                        <input type="password" name="azuracast_api_key" class="form-input"
                               value="{{ $settings['azuracast_api_key'] }}"
                               placeholder="Enter API key to update">
                        <small style="color: var(--color-text-muted);">Leave blank to keep current key. Required for song requests.</small>
                    </div>

                    @if($connectionStatus['azuracast']['success'] && isset($connectionStatus['azuracast']['data']))
                        <div style="margin-top: 1rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin-bottom: 0.25rem;">
                                <strong>Station:</strong> {{ $connectionStatus['azuracast']['data']['station_name'] ?? 'N/A' }}
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">
                                <strong>Requests:</strong>
                                @if($connectionStatus['azuracast']['data']['requests_enabled'] ?? false)
                                    <span style="color: var(--color-success);">Enabled</span>
                                @else
                                    <span style="color: var(--color-warning);">Disabled</span>
                                @endif
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Icecast Settings Card -->
            <div class="card server-config" data-server="icecast">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-icicles" style="margin-right: 0.5rem; color: #06b6d4;"></i> Icecast Settings</h3>
                    @if($connectionStatus['icecast']['success'])
                        <span class="badge badge-success">Connected</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Host</label>
                        <input type="text" name="icecast_host" class="form-input"
                               value="{{ $settings['icecast_host'] }}"
                               placeholder="localhost or your-server.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Port</label>
                        <input type="number" name="icecast_port" class="form-input"
                               value="{{ $settings['icecast_port'] }}"
                               placeholder="8000" min="1" max="65535">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Mount Point</label>
                        <input type="text" name="icecast_mount" class="form-input"
                               value="{{ $settings['icecast_mount'] }}"
                               placeholder="/stream">
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="icecast_ssl" id="icecastSsl" class="form-check-input" value="1"
                                   {{ $settings['icecast_ssl'] ? 'checked' : '' }}>
                            <label for="icecastSsl" class="form-label" style="margin-bottom: 0;">Use SSL (HTTPS)</label>
                        </div>
                    </div>

                    @if($connectionStatus['icecast']['success'] && isset($connectionStatus['icecast']['data']))
                        <div style="margin-top: 1rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin-bottom: 0.25rem;">
                                <strong>Listeners:</strong> {{ $connectionStatus['icecast']['data']['listeners'] ?? 0 }}
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">
                                <strong>Bitrate:</strong> {{ $connectionStatus['icecast']['data']['bitrate'] ?? 0 }} kbps
                            </p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Shoutcast Settings Card -->
            <div class="card server-config" data-server="shoutcast">
                <div class="card-header">
                    <h3 class="card-title"><i class="fas fa-volume-up" style="margin-right: 0.5rem; color: #f59e0b;"></i> Shoutcast Settings</h3>
                    @if($connectionStatus['shoutcast']['success'])
                        <span class="badge badge-success">Connected</span>
                    @endif
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Host</label>
                        <input type="text" name="shoutcast_host" class="form-input"
                               value="{{ $settings['shoutcast_host'] }}"
                               placeholder="localhost or your-server.com">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Port</label>
                        <input type="number" name="shoutcast_port" class="form-input"
                               value="{{ $settings['shoutcast_port'] }}"
                               placeholder="8000" min="1" max="65535">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Stream ID</label>
                        <input type="number" name="shoutcast_stream_id" class="form-input"
                               value="{{ $settings['shoutcast_stream_id'] }}"
                               placeholder="1" min="1" max="999">
                        <small style="color: var(--color-text-muted);">For multi-stream Shoutcast servers (v2+).</small>
                    </div>

                    <div class="form-group">
                        <div class="form-check">
                            <input type="checkbox" name="shoutcast_ssl" id="shoutcastSsl" class="form-check-input" value="1"
                                   {{ $settings['shoutcast_ssl'] ? 'checked' : '' }}>
                            <label for="shoutcastSsl" class="form-label" style="margin-bottom: 0;">Use SSL (HTTPS)</label>
                        </div>
                    </div>

                    @if($connectionStatus['shoutcast']['success'] && isset($connectionStatus['shoutcast']['data']))
                        <div style="margin-top: 1rem; padding: 0.75rem; background: var(--color-bg-tertiary); border-radius: 6px;">
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin-bottom: 0.25rem;">
                                <strong>Listeners:</strong> {{ $connectionStatus['shoutcast']['data']['listeners'] ?? 0 }}
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin-bottom: 0.25rem;">
                                <strong>Now Playing:</strong> {{ $connectionStatus['shoutcast']['data']['current_song'] ?? 'N/A' }}
                            </p>
                            <p style="font-size: 0.875rem; color: var(--color-text-secondary); margin: 0;">
                                <strong>Genre:</strong> {{ $connectionStatus['shoutcast']['data']['genre'] ?? 'N/A' }}
                            </p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div style="margin-top: 1.5rem; display: flex; gap: 1rem;">
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Save Settings
            </button>
        </div>
    </form>

    <script>
        // Highlight the active server configuration
        document.addEventListener('DOMContentLoaded', function() {
            const serverSelect = document.getElementById('serverTypeSelect');
            const updateHighlight = () => {
                document.querySelectorAll('.server-config').forEach(card => {
                    card.style.borderColor = card.dataset.server === serverSelect.value
                        ? 'var(--color-accent)'
                        : 'var(--color-border)';
                });
            };

            serverSelect.addEventListener('change', updateHighlight);
            updateHighlight();
        });
    </script>
</x-admin.layouts.app>
