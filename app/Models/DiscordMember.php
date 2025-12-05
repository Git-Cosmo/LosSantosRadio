<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DiscordMember extends Model
{
    protected $fillable = [
        'discord_id',
        'user_id',
        'username',
        'discriminator',
        'avatar',
        'role_ids',
        'joined_at',
        'is_synced',
    ];

    protected $casts = [
        'role_ids' => 'array',
        'joined_at' => 'datetime',
        'is_synced' => 'boolean',
    ];

    /**
     * Get the linked user.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to synced members.
     */
    public function scopeSynced($query)
    {
        return $query->where('is_synced', true);
    }

    /**
     * Get the full Discord tag.
     */
    public function getTagAttribute(): string
    {
        if ($this->discriminator && $this->discriminator !== '0') {
            return $this->username.'#'.$this->discriminator;
        }

        return $this->username;
    }

    /**
     * Get the avatar URL.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return "https://cdn.discordapp.com/avatars/{$this->discord_id}/{$this->avatar}.png";
        }

        return 'https://cdn.discordapp.com/embed/avatars/0.png';
    }

    /**
     * Check if the member has a specific role.
     */
    public function hasRole(string $roleId): bool
    {
        return in_array($roleId, $this->role_ids ?? []);
    }
}
