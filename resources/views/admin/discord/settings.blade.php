<x-admin.layouts.app :title="'Discord Settings'">
    <div class="admin-header">
        <h1>Discord Bot Settings</h1>
    </div>

    <div class="card">
        <div class="card-header" style="display: flex; justify-content: space-between; align-items: center;">
            <h2 class="card-title">Bot Configuration</h2>
            @if($botStatus)
                <div style="display: flex; align-items: center; gap: 0.5rem;">
                    @if($botEnabled)
                        <span class="badge badge-live pulse-animation" style="background: #43b581;">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            Bot Running
                        </span>
                    @else
                        <span class="badge" style="background: #f04747; color: white;">
                            <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                            Bot Stopped
                        </span>
                    @endif
                    <div style="display: flex; gap: 0.25rem;">
                        @if($botEnabled)
                            <form action="{{ route('admin.discord.stop') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-danger" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="return confirm('Are you sure you want to stop the bot?')">
                                    <i class="fas fa-stop"></i> Stop Bot
                                </button>
                            </form>
                        @else
                            <form action="{{ route('admin.discord.start') }}" method="POST" style="margin: 0;">
                                @csrf
                                <button type="submit" class="btn btn-success" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="return confirm('Are you sure you want to start the bot?')">
                                    <i class="fas fa-play"></i> Start Bot
                                </button>
                            </form>
                        @endif
                        <form action="{{ route('admin.discord.restart') }}" method="POST" style="margin: 0;">
                            @csrf
                            <button type="submit" class="btn btn-secondary" style="padding: 0.375rem 0.75rem; font-size: 0.875rem;" onclick="return confirm('Are you sure you want to restart the bot connection?')">
                                <i class="fas fa-sync-alt"></i> Restart
                            </button>
                        </form>
                    </div>
                </div>
            @else
                <span class="badge" style="background: #f04747; color: white;">
                    <i class="fas fa-circle" style="font-size: 0.5rem;"></i>
                    Bot Offline
                </span>
            @endif
        </div>
        <div class="card-body">
            <form action="{{ route('admin.discord.settings.update') }}" method="POST">
                @csrf

                <div class="alert" style="background-color: var(--color-bg-tertiary); border: 1px solid var(--color-border); margin-bottom: 1.5rem;">
                    <p style="color: var(--color-text-secondary); font-size: 0.875rem;">
                        <i class="fas fa-shield-alt" style="color: var(--color-accent);"></i>
                        <strong>Important:</strong> The Discord bot token and guild ID must be configured via environment variables only. For security reasons, they cannot be set or overridden from this form. Use the Start/Stop buttons above to control the bot.
                    </p>
                </div>

                <div class="form-group">
                    <label class="form-label">Bot Token</label>
                    <div class="form-input" style="background-color: var(--color-bg-tertiary); cursor: not-allowed;">
                        {{ config('services.discord.bot_token') ? '••••••••' . substr(config('services.discord.bot_token'), -4) : 'Not configured' }}
                    </div>
                    <small style="color: var(--color-text-muted);">Set via <code>DISCORD_BOT_TOKEN</code> in .env file</small>
                </div>

                <div class="form-group">
                    <label class="form-label">Guild (Server) ID</label>
                    <div class="form-input" style="background-color: var(--color-bg-tertiary); cursor: not-allowed;">
                        {{ config('services.discord.guild_id') ?: 'Not configured' }}
                    </div>
                    <small style="color: var(--color-text-muted);">Set via <code>DISCORD_GUILD_ID</code> in .env file</small>
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
