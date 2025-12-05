<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DiscordLog;
use App\Models\DiscordMember;
use App\Models\DiscordRole;
use App\Models\Setting;
use App\Services\DiscordBotService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DiscordBotController extends Controller
{
    public function __construct(
        protected DiscordBotService $discord
    ) {}

    /**
     * Display Discord bot dashboard.
     */
    public function index(): View
    {
        $stats = $this->discord->getStats();

        return view('admin.discord.index', [
            'stats' => $stats,
            'roles' => DiscordRole::orderBy('position', 'desc')->get(),
            'members' => DiscordMember::with('user')->orderBy('username')->paginate(20),
            'logs' => DiscordLog::orderBy('created_at', 'desc')->take(20)->get(),
        ]);
    }

    /**
     * Sync roles from Discord.
     */
    public function syncRoles(): RedirectResponse
    {
        if (! $this->discord->isConfigured()) {
            return redirect()->route('admin.discord.index')
                ->with('error', 'Discord bot is not configured. Please add bot token and guild ID in .env file.');
        }

        $count = $this->discord->syncRoles();

        return redirect()->route('admin.discord.index')
            ->with('success', "Synced {$count} roles from Discord.");
    }

    /**
     * Sync members from Discord.
     */
    public function syncUsers(): RedirectResponse
    {
        if (! $this->discord->isConfigured()) {
            return redirect()->route('admin.discord.index')
                ->with('error', 'Discord bot is not configured. Please add bot token and guild ID in .env file.');
        }

        $count = $this->discord->syncMembers();

        return redirect()->route('admin.discord.index')
            ->with('success', "Synced {$count} members from Discord.");
    }

    /**
     * Display Discord settings page.
     */
    public function settings(): View
    {
        // Bot token and guild ID are read from environment variables only for security
        $settings = [
            'discord_log_channel' => Setting::get('discord_log_channel'),
            'discord_welcome_channel' => Setting::get('discord_welcome_channel'),
            'discord_auto_sync_enabled' => Setting::get('discord_auto_sync_enabled', false),
        ];

        return view('admin.discord.settings', [
            'settings' => $settings,
        ]);
    }

    /**
     * Update Discord settings.
     * Note: Bot token and guild ID cannot be updated via the admin panel for security reasons.
     */
    public function updateSettings(Request $request): RedirectResponse
    {
        // Only allow non-sensitive settings to be updated via the admin panel
        // Bot token and guild ID must be set via environment variables
        $validated = $request->validate([
            'discord_log_channel' => 'nullable|string|max:25',
            'discord_welcome_channel' => 'nullable|string|max:25',
        ]);

        foreach ($validated as $key => $value) {
            Setting::set($key, $value);
        }

        Setting::set('discord_auto_sync_enabled', $request->boolean('discord_auto_sync_enabled'));

        // Clear Discord cache to pick up new settings
        $this->discord->clearCache();

        return redirect()->route('admin.discord.settings')
            ->with('success', 'Discord settings updated successfully.');
    }
}
