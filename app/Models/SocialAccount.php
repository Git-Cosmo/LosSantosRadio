<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;

class SocialAccount extends Model
{
    use HasFactory, LogsActivity;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'provider',
        'provider_id',
        'provider_nickname',
        'provider_avatar',
        'access_token',
        'refresh_token',
        'token_expires_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'access_token',
        'refresh_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'token_expires_at' => 'datetime',
        ];
    }

    /**
     * Get the user that owns the social account.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['provider', 'provider_id'])
            ->logOnlyDirty();
    }

    /**
     * Supported social providers.
     */
    public static function supportedProviders(): array
    {
        return ['discord', 'twitch', 'steam', 'battlenet'];
    }

    /**
     * Get provider display name.
     */
    public function getProviderDisplayNameAttribute(): string
    {
        return match ($this->provider) {
            'discord' => 'Discord',
            'twitch' => 'Twitch',
            'steam' => 'Steam',
            'battlenet' => 'Battle.net',
            default => ucfirst($this->provider),
        };
    }

    /**
     * Get provider icon class (for Font Awesome or similar).
     */
    public function getProviderIconAttribute(): string
    {
        return match ($this->provider) {
            'discord' => 'fab fa-discord',
            'twitch' => 'fab fa-twitch',
            'steam' => 'fab fa-steam',
            'battlenet' => 'fab fa-battle-net',
            default => 'fas fa-user',
        };
    }

    /**
     * Get provider color for UI elements.
     */
    public function getProviderColorAttribute(): string
    {
        return match ($this->provider) {
            'discord' => '#5865F2',
            'twitch' => '#9146FF',
            'steam' => '#1b2838',
            'battlenet' => '#00AEFF',
            default => '#6B7280',
        };
    }
}
