<x-admin.layouts.app :title="'Discord Settings'">
    <div class="admin-header">
        <h1>Discord Bot Settings</h1>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ route('admin.discord.settings.update') }}" method="POST">
                @csrf

                <div class="alert" style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border); margin-bottom: 1.5rem;">
                    <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                        <i class="fas fa-info-circle" style="color: var(--color-accent);"></i>
                        Discord bot settings are primarily configured through environment variables. You can override them here for quick testing.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label" for="discord_bot_token">Bot Token</label>
                    <input type="password" name="discord_bot_token" id="discord_bot_token" class="form-input" value="{{ $settings['discord_bot_token'] ?? '' }}" placeholder="Leave empty to use .env value">
                    <small style="color: var(--color-text-muted);">Get this from the Discord Developer Portal</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="discord_guild_id">Guild (Server) ID</label>
                    <input type="text" name="discord_guild_id" id="discord_guild_id" class="form-input" value="{{ $settings['discord_guild_id'] ?? '' }}" placeholder="Leave empty to use .env value">
                    <small style="color: var(--color-text-muted);">Right-click your server and copy ID (Developer Mode must be enabled)</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="discord_log_channel">Log Channel ID</label>
                    <input type="text" name="discord_log_channel" id="discord_log_channel" class="form-input" value="{{ $settings['discord_log_channel'] ?? '' }}">
                    <small style="color: var(--color-text-muted);">Channel ID for bot activity logs</small>
                </div>

                <div class="form-group">
                    <label class="form-label" for="discord_welcome_channel">Welcome Channel ID</label>
                    <input type="text" name="discord_welcome_channel" id="discord_welcome_channel" class="form-input" value="{{ $settings['discord_welcome_channel'] ?? '' }}">
                    <small style="color: var(--color-text-muted);">Channel ID for welcome messages</small>
                </div>

                <div class="form-group">
                    <label class="form-check">
                        <input type="checkbox" name="discord_auto_sync_enabled" value="1" class="form-check-input" {{ ($settings['discord_auto_sync_enabled'] ?? false) ? 'checked' : '' }}>
                        <span class="form-check-label">Enable Auto-Sync</span>
                    </label>
                    <small style="color: var(--color-text-muted); display: block; margin-top: 0.25rem;">Automatically sync roles and members periodically</small>
                </div>

                <div style="display: flex; gap: 0.5rem;">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Settings
                    </button>
                    <a href="{{ route('admin.discord.index') }}" class="btn btn-secondary">Cancel</a>
                </div>
            </form>
        </div>
    </div>

    <div class="card" style="margin-top: 1.5rem;">
        <div class="card-header">
            <h2 class="card-title">Environment Variables</h2>
        </div>
        <div class="card-body">
            <p style="color: var(--color-text-secondary); margin-bottom: 1rem;">Add these to your <code>.env</code> file for permanent configuration:</p>
            <pre style="background: var(--color-bg-tertiary); padding: 1rem; border-radius: 6px; overflow-x: auto; font-size: 0.875rem;">DISCORD_BOT_TOKEN=your_bot_token_here
DISCORD_GUILD_ID=your_guild_id_here</pre>
        </div>
    </div>
</x-admin.layouts.app>
