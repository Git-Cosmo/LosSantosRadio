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

    @if($errors->any())
        <div class="alert alert-error">
            <i class="fas fa-exclamation-triangle"></i>
            <strong>Please correct the following errors:</strong>
            <ul style="margin: 0.5rem 0 0 1.5rem;">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
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
                               value="{{ old('settings.site_name', $settings['site_name'] ?? '') }}" 
                               class="form-input @error('settings.site_name') is-invalid @enderror">
                        @error('settings.site_name')
                            <span class="error-message" style="color: var(--color-error); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Site Description</span>
                            <span class="label-desc">Short description for SEO</span>
                        </label>
                        <textarea name="settings[site_description]" 
                                  class="form-input @error('settings.site_description') is-invalid @enderror" 
                                  rows="3">{{ old('settings.site_description', $settings['site_description'] ?? '') }}</textarea>
                        @error('settings.site_description')
                            <span class="error-message" style="color: var(--color-error); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Contact Email</span>
                            <span class="label-desc">Email for support inquiries</span>
                        </label>
                        <input type="email" 
                               name="settings[contact_email]" 
                               value="{{ old('settings.contact_email', $settings['contact_email'] ?? '') }}" 
                               class="form-input @error('settings.contact_email') is-invalid @enderror">
                        @error('settings.contact_email')
                            <span class="error-message" style="color: var(--color-error); font-size: 0.875rem; margin-top: 0.25rem; display: block;">{{ $message }}</span>
                        @enderror
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
                            <option value="none" {{ ($settings['site_theme'] ?? '') === 'none' ? 'selected' : '' }}>
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
                                   {{ ($settings['enable_comments'] ?? false) ? 'checked' : '' }}>
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
                                   {{ ($settings['enable_song_requests'] ?? false) ? 'checked' : '' }}>
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
                                   {{ ($settings['enable_polls'] ?? false) ? 'checked' : '' }}>
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
                               value="{{ $settings['default_station_id'] ?? '' }}" 
                               class="form-input">
                    </div>

                    <div class="setting-item">
                        <label class="setting-label">
                            <span class="label-text">Listener Count Update Interval</span>
                            <span class="label-desc">Seconds between updates</span>
                        </label>
                        <input type="number" 
                               name="settings[listener_update_interval]" 
                               value="{{ $settings['listener_update_interval'] ?? '' }}" 
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
                               value="{{ $settings['guest_request_limit'] ?? '' }}" 
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
                               value="{{ $settings['user_request_limit'] ?? '' }}" 
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
                               value="{{ $settings['guest_lyrics_limit'] ?? '' }}" 
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
            <a href="{{ route('admin.settings.advanced') }}" class="btn btn-secondary">
                <i class="fas fa-cog"></i> Advanced Settings (Key-Value Editor)
            </a>
        </div>
    </div></x-admin.layouts.app>
