<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscordRole extends Model
{
    protected $fillable = [
        'discord_id',
        'name',
        'color',
        'position',
        'permissions',
        'is_synced',
    ];

    protected $casts = [
        'position' => 'integer',
        'permissions' => 'array',
        'is_synced' => 'boolean',
    ];

    /**
     * Scope to synced roles.
     */
    public function scopeSynced($query)
    {
        return $query->where('is_synced', true);
    }

    /**
     * Get the role color as hex.
     */
    public function getHexColorAttribute(): string
    {
        return $this->color ?? '#99AAB5';
    }
}
