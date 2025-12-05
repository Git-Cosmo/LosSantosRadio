<?php

namespace App\Services;

use App\Models\DiscordLog;
use App\Models\DiscordMember;
use App\Models\DiscordRole;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class DiscordBotService
{
    protected string $apiUrl = 'https://discord.com/api/v10';

    protected ?string $botToken;

    protected ?string $guildId;

    protected int $cacheTtl = 300; // 5 minutes

    public function __construct()
    {
        $this->botToken = config('services.discord.bot_token');
        $this->guildId = config('services.discord.guild_id');
    }

    /**
     * Check if the bot is configured.
     */
    public function isConfigured(): bool
    {
        return ! empty($this->botToken) && ! empty($this->guildId);
    }

    /**
     * Make an API request to Discord.
     */
    protected function request(string $method, string $endpoint, array $data = []): ?array
    {
        if (! $this->isConfigured()) {
            Log::warning('Discord: Bot is not configured');

            return null;
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => "Bot {$this->botToken}",
            ])->timeout(30);

            $response = match (strtoupper($method)) {
                'GET' => $response->get("{$this->apiUrl}{$endpoint}", $data),
                'POST' => $response->post("{$this->apiUrl}{$endpoint}", $data),
                'PUT' => $response->put("{$this->apiUrl}{$endpoint}", $data),
                'DELETE' => $response->delete("{$this->apiUrl}{$endpoint}"),
                default => throw new \InvalidArgumentException("Invalid HTTP method: {$method}"),
            };

            if ($response->failed()) {
                Log::error('Discord API Error', [
                    'endpoint' => $endpoint,
                    'status' => $response->status(),
                    'body' => $response->body(),
                ]);

                return null;
            }

            return $response->json();
        } catch (\Exception $e) {
            Log::error('Discord API Exception', ['error' => $e->getMessage()]);

            return null;
        }
    }

    /**
     * Get the current bot user info.
     */
    public function getBotUser(): ?array
    {
        return Cache::remember('discord.bot.user', $this->cacheTtl, function () {
            return $this->request('GET', '/users/@me');
        });
    }

    /**
     * Get guild (server) info.
     */
    public function getGuild(): ?array
    {
        return Cache::remember('discord.guild', $this->cacheTtl, function () {
            return $this->request('GET', "/guilds/{$this->guildId}");
        });
    }

    /**
     * Get all roles from the guild.
     */
    public function getGuildRoles(): ?array
    {
        return Cache::remember('discord.guild.roles', $this->cacheTtl, function () {
            return $this->request('GET', "/guilds/{$this->guildId}/roles");
        });
    }

    /**
     * Sync roles from Discord to database.
     */
    public function syncRoles(): int
    {
        $roles = $this->getGuildRoles();

        if (! $roles) {
            DiscordLog::error('sync_roles', 'Failed to fetch roles from Discord');

            return 0;
        }

        $synced = 0;

        foreach ($roles as $role) {
            DiscordRole::updateOrCreate(
                ['discord_id' => $role['id']],
                [
                    'name' => $role['name'],
                    'color' => $this->intToHexColor($role['color']),
                    'position' => $role['position'],
                    'permissions' => ['raw' => $role['permissions']],
                    'is_synced' => true,
                ]
            );

            $synced++;
        }

        DiscordLog::sync('sync_roles', "Synced {$synced} roles from Discord", ['count' => $synced]);

        return $synced;
    }

    /**
     * Get all members from the guild.
     */
    public function getGuildMembers(int $limit = 1000): Collection
    {
        $members = collect();
        $after = '0';

        while (true) {
            $batch = $this->request('GET', "/guilds/{$this->guildId}/members", [
                'limit' => min($limit - $members->count(), 1000),
                'after' => $after,
            ]);

            if (! $batch || empty($batch)) {
                break;
            }

            $members = $members->merge($batch);

            if (count($batch) < 1000 || $members->count() >= $limit) {
                break;
            }

            $after = end($batch)['user']['id'];
        }

        return $members;
    }

    /**
     * Sync members from Discord to database.
     */
    public function syncMembers(): int
    {
        $members = $this->getGuildMembers();

        if ($members->isEmpty()) {
            DiscordLog::error('sync_members', 'Failed to fetch members from Discord');

            return 0;
        }

        $synced = 0;

        foreach ($members as $member) {
            $user = $member['user'] ?? null;
            if (! $user) {
                continue;
            }

            // Try to find linked user by Discord social account
            $linkedUser = User::whereHas('socialAccounts', function ($query) use ($user) {
                $query->where('provider', 'discord')
                    ->where('provider_id', $user['id']);
            })->first();

            DiscordMember::updateOrCreate(
                ['discord_id' => $user['id']],
                [
                    'user_id' => $linkedUser?->id,
                    'username' => $user['username'],
                    'discriminator' => $user['discriminator'] ?? null,
                    'avatar' => $user['avatar'] ?? null,
                    'role_ids' => $member['roles'] ?? [],
                    'joined_at' => ! empty($member['joined_at']) ? \Carbon\Carbon::parse($member['joined_at']) : null,
                    'is_synced' => true,
                ]
            );

            $synced++;
        }

        DiscordLog::sync('sync_members', "Synced {$synced} members from Discord", ['count' => $synced]);

        return $synced;
    }

    /**
     * Create a role in Discord.
     */
    public function createRole(string $name, ?string $color = null, ?array $permissions = null): ?array
    {
        $data = ['name' => $name];

        if ($color) {
            $data['color'] = $this->hexToIntColor($color);
        }

        if ($permissions) {
            $data['permissions'] = $permissions;
        }

        $result = $this->request('POST', "/guilds/{$this->guildId}/roles", $data);

        if ($result) {
            DiscordLog::info('create_role', "Created role: {$name}", $result);

            // Sync the new role to database
            DiscordRole::create([
                'discord_id' => $result['id'],
                'name' => $result['name'],
                'color' => $this->intToHexColor($result['color']),
                'position' => $result['position'],
                'is_synced' => true,
            ]);
        }

        return $result;
    }

    /**
     * Assign a role to a member.
     */
    public function assignRole(string $userId, string $roleId): bool
    {
        $result = $this->request('PUT', "/guilds/{$this->guildId}/members/{$userId}/roles/{$roleId}");

        if ($result !== null || $result === []) {
            DiscordLog::info('assign_role', "Assigned role {$roleId} to user {$userId}");

            return true;
        }

        return false;
    }

    /**
     * Remove a role from a member.
     */
    public function removeRole(string $userId, string $roleId): bool
    {
        $result = $this->request('DELETE', "/guilds/{$this->guildId}/members/{$userId}/roles/{$roleId}");

        if ($result !== null || $result === []) {
            DiscordLog::info('remove_role', "Removed role {$roleId} from user {$userId}");

            return true;
        }

        return false;
    }

    /**
     * Send a message to a channel.
     */
    public function sendMessage(string $channelId, string $content, ?array $embed = null): ?array
    {
        $data = ['content' => $content];

        if ($embed) {
            $data['embeds'] = [$embed];
        }

        return $this->request('POST', "/channels/{$channelId}/messages", $data);
    }

    /**
     * Get bot statistics.
     */
    public function getStats(): array
    {
        $bot = $this->getBotUser();
        $guild = $this->getGuild();

        return [
            'bot' => $bot ? [
                'username' => $bot['username'],
                'discriminator' => $bot['discriminator'] ?? null,
                'id' => $bot['id'],
            ] : null,
            'guild' => $guild ? [
                'name' => $guild['name'],
                'id' => $guild['id'],
                'member_count' => $guild['approximate_member_count'] ?? 'N/A',
            ] : null,
            'configured' => $this->isConfigured(),
            'local_members' => DiscordMember::count(),
            'local_roles' => DiscordRole::count(),
        ];
    }

    /**
     * Convert integer color to hex.
     */
    protected function intToHexColor(int $color): string
    {
        return '#'.str_pad(dechex($color), 6, '0', STR_PAD_LEFT);
    }

    /**
     * Convert hex color to integer.
     */
    protected function hexToIntColor(string $hex): int
    {
        return hexdec(ltrim($hex, '#'));
    }

    /**
     * Clear Discord cache.
     */
    public function clearCache(): void
    {
        Cache::forget('discord.bot.user');
        Cache::forget('discord.guild');
        Cache::forget('discord.guild.roles');
    }
}
