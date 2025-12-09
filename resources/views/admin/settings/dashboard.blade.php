<x-admin.layouts.app title="Settings">
    <div class="admin-header">
        <h1>⚙️ Settings Dashboard</h1>
        <p style="color: var(--color-text-secondary); margin-top: 0.5rem;">
            Configure your Los Santos Radio instance
        </p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">
            <i class="fas fa-check-circle"></i> {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="alert alert-error">
            <i class="fas fa-exclamation-circle"></i> {{ session('error') }}
        </div>
    @endif

    <form action="{{ route('admin.settings.update-all') }}" method="POST" id="settingsForm">
        @csrf
        @method('PUT')

        <!-- General Settings -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🌐 General Settings</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Site Name</span>
                            <span class="label-desc">The name of your radio station</span>
                        </label>
                        <input type="text" 
                               name="settings[site_name]" 
                               value="{{ $settings['site_name'] ?? 'Los Santos Radio' }}" 
                               class="form-input">
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Site Description</span>
                            <span class="label-desc">Short description for SEO</span>
                        </label>
                        <textarea name="settings[site_description]" 
                                  class="form-input" 
                                  rows="3">{{ $settings['site_description'] ?? '' }}</textarea>
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Contact Email</span>
                            <span class="label-desc">Email for support inquiries</span>
                        </label>
                        <input type="email" 
                               name="settings[contact_email]" 
                               value="{{ $settings['contact_email'] ?? '' }}" 
                               class="form-input">
                    </div>
                </div>
            </div>
        </div>

        <!-- Theme Settings -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🎨 Theme Settings</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Active Seasonal Theme</span>
                            <span class="label-desc">Apply festive overlays site-wide</span>
                        </label>
                        <select name="settings[site_theme]" class="form-select">
                            <option value="none" {{ ($settings['site_theme'] ?? 'none') === 'none' ? 'selected' : '' }}>
                                No Theme
                            </option>
                            <option value="christmas" {{ ($settings['site_theme'] ?? '') === 'christmas' ? 'selected' : '' }}>
                                🎄 Christmas Theme
                            </option>
                            <option value="newyear" {{ ($settings['site_theme'] ?? '') === 'newyear' ? 'selected' : '' }}>
                                🎉 New Year Theme
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>

        <!-- Feature Toggles -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🎛️ Feature Toggles</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="setting-item checkbox-item">
                        <label class="checkbox-label">
                            <input type="hidden" name="settings[enable_comments]" value="0">
                            <input type="checkbox" 
                                   name="settings[enable_comments]" 
                                   value="1"
                                   {{ ($settings['enable_comments'] ?? true) ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            <div class="checkbox-text">
                                <span class="label-text">Enable Comments</span>
                                <span class="label-desc">Allow users to comment on news and events</span>
                            </div>
                        </label>
                    </div>

                    <div class="setting-item checkbox-item">
                        <label class="checkbox-label">
                            <input type="hidden" name="settings[enable_song_requests]" value="0">
                            <input type="checkbox" 
                                   name="settings[enable_song_requests]" 
                                   value="1"
                                   {{ ($settings['enable_song_requests'] ?? true) ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            <div class="checkbox-text">
                                <span class="label-text">Enable Song Requests</span>
                                <span class="label-desc">Allow listeners to request songs</span>
                            </div>
                        </label>
                    </div>

                    <div class="setting-item checkbox-item">
                        <label class="checkbox-label">
                            <input type="hidden" name="settings[enable_polls]" value="0">
                            <input type="checkbox" 
                                   name="settings[enable_polls]" 
                                   value="1"
                                   {{ ($settings['enable_polls'] ?? true) ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            <div class="checkbox-text">
                                <span class="label-text">Enable Polls</span>
                                <span class="label-desc">Allow community polls and voting</span>
                            </div>
                        </label>
                    </div>

                    <div class="setting-item checkbox-item">
                        <label class="checkbox-label">
                            <input type="hidden" name="settings[maintenance_mode]" value="0">
                            <input type="checkbox" 
                                   name="settings[maintenance_mode]" 
                                   value="1"
                                   {{ ($settings['maintenance_mode'] ?? false) ? 'checked' : '' }}>
                            <span class="checkbox-custom"></span>
                            <div class="checkbox-text">
                                <span class="label-text">Maintenance Mode</span>
                                <span class="label-desc">Show maintenance page to visitors</span>
                            </div>
                        </label>
                    </div>
                </div>
            </div>
        </div>

        <!-- Radio Settings -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>📻 Radio Settings</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Default Station ID</span>
                            <span class="label-desc">AzuraCast station identifier</span>
                        </label>
                        <input type="number" 
                               name="settings[default_station_id]" 
                               value="{{ $settings['default_station_id'] ?? 1 }}" 
                               class="form-input">
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Listener Count Update Interval</span>
                            <span class="label-desc">Seconds between updates</span>
                        </label>
                        <input type="number" 
                               name="settings[listener_update_interval]" 
                               value="{{ $settings['listener_update_interval'] ?? 15 }}" 
                               class="form-input"
                               min="5"
                               max="60">
                    </div>
                </div>
            </div>
        </div>

        <!-- Rate Limits -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3>🚦 Rate Limits</h3>
            </div>
            <div class="card-body">
                <div class="settings-grid">
                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Guest Song Requests Per Hour</span>
                            <span class="label-desc">Maximum requests for non-logged-in users</span>
                        </label>
                        <input type="number" 
                               name="settings[guest_request_limit]" 
                               value="{{ $settings['guest_request_limit'] ?? 3 }}" 
                               class="form-input"
                               min="0"
                               max="20">
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">User Song Requests Per Hour</span>
                            <span class="label-desc">Maximum requests for logged-in users</span>
                        </label>
                        <input type="number" 
                               name="settings[user_request_limit]" 
                               value="{{ $settings['user_request_limit'] ?? 10 }}" 
                               class="form-input"
                               min="1"
                               max="50">
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Guest Lyrics View Limit</span>
                            <span class="label-desc">Songs per session for guests</span>
                        </label>
                        <input type="number" 
                               name="settings[guest_lyrics_limit]" 
                               value="{{ $settings['guest_lyrics_limit'] ?? 4 }}" 
                               class="form-input"
                               min="0"
                               max="20">
                    </div>
                </div>
            </div>
        </div>

        <!-- Save Button (Sticky) -->
        <div class="settings-save-bar">
            <div class="save-bar-content">
                <div class="save-bar-info">
                    <i class="fas fa-info-circle"></i>
                    <span>Changes will take effect immediately after saving</span>
                </div>
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="fas fa-save"></i> Save All Settings
                </button>
            </div>
        </div>
    </form>

    <!-- Link to advanced settings -->
    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-body" style="text-align: center;">
            <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">
                Need to add or edit individual settings?
            </p>
            <a href="{{ route('admin.settings.index') }}" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Advanced Settings (Key-Value Editor)
            </a>
        </div>
    </div>

    <style>
        .settings-grid {
            display: grid;
            gap: 1.5rem;
        }

        .setting-item {
            display: flex;
            flex-direction: column;
        }

        .setting-item.checkbox-item {
            border: 1px solid var(--color-border);
            border-radius: 8px;
            padding: 1rem;
            transition: all 0.2s ease;
        }

        .setting-item.checkbox-item:hover {
            background: var(--color-bg-hover);
            border-color: var(--color-accent);
        }

        .setting-label {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            margin-bottom: 0.5rem;
        }

        .checkbox-label {
            display: flex;
            align-items: flex-start;
            gap: 0.75rem;
            cursor: pointer;
            margin: 0;
        }

        .label-text {
            font-weight: 600;
            color: var(--color-text-primary);
            font-size: 0.9375rem;
        }

        .label-desc {
            font-size: 0.8125rem;
            color: var(--color-text-secondary);
            line-height: 1.4;
        }

        .checkbox-custom {
            width: 20px;
            height: 20px;
            border: 2px solid var(--color-border);
            border-radius: 4px;
            flex-shrink: 0;
            position: relative;
            transition: all 0.2s ease;
            margin-top: 2px;
        }

        .checkbox-label input[type="checkbox"] {
            position: absolute;
            opacity: 0;
            width: 0;
            height: 0;
        }

        .checkbox-label input[type="checkbox"]:checked + .checkbox-custom {
            background: var(--color-accent);
            border-color: var(--color-accent);
        }

        .checkbox-label input[type="checkbox"]:checked + .checkbox-custom::after {
            content: '✓';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 14px;
            font-weight: bold;
        }

        .checkbox-text {
            display: flex;
            flex-direction: column;
            gap: 0.25rem;
            flex: 1;
        }

        .settings-save-bar {
            position: sticky;
            bottom: 0;
            left: 0;
            right: 0;
            background: var(--color-bg-secondary);
            border-top: 1px solid var(--color-border);
            padding: 1rem 0;
            margin: 2rem -1.5rem -1.5rem;
            z-index: 100;
        }

        .save-bar-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 1rem;
            padding: 0 1.5rem;
            flex-wrap: wrap;
        }

        .save-bar-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            color: var(--color-text-secondary);
            font-size: 0.875rem;
        }

        @media (max-width: 768px) {
            .save-bar-content {
                flex-direction: column;
                align-items: stretch;
            }

            .save-bar-info {
                justify-content: center;
            }

            .settings-save-bar .btn {
                width: 100%;
            }
        }
    </style>
</x-admin.layouts.app>
