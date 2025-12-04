<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\EloquentSortable\Sortable;
use Spatie\EloquentSortable\SortableTrait;

class SongRequest extends Model implements Sortable
{
    use HasFactory, LogsActivity, SortableTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'song_id',
        'song_title',
        'song_artist',
        'ip_address',
        'session_id',
        'guest_email',
        'status',
        'queue_order',
        'azuracast_request_id',
        'played_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'played_at' => 'datetime',
            'queue_order' => 'integer',
        ];
    }

    /**
     * Sortable configuration.
     */
    public array $sortable = [
        'order_column_name' => 'queue_order',
        'sort_when_creating' => true,
        'sort_on_has_many' => true,
    ];

    /**
     * Build sort query - only sort pending requests.
     */
    public function buildSortQuery()
    {
        return static::query()->where('status', self::STATUS_PENDING);
    }

    /**
     * Possible request statuses.
     */
    public const STATUS_PENDING = 'pending';

    public const STATUS_PLAYING = 'playing';

    public const STATUS_PLAYED = 'played';

    public const STATUS_REJECTED = 'rejected';

    public const STATUS_CANCELLED = 'cancelled';

    /**
     * Get the user that made the request (if logged in).
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
            ->logOnly(['status', 'song_title', 'song_artist'])
            ->logOnlyDirty();
    }

    /**
     * Scope to filter pending requests.
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }

    /**
     * Scope to filter requests by a specific user.
     */
    public function scopeByUser($query, User $user)
    {
        return $query->where('user_id', $user->id);
    }

    /**
     * Scope to filter requests by guest identifiers.
     */
    public function scopeByGuest($query, string $ip, string $sessionId)
    {
        return $query->where(function ($q) use ($ip, $sessionId) {
            $q->where('ip_address', $ip)
                ->orWhere('session_id', $sessionId);
        })->whereNull('user_id');
    }

    /**
     * Scope to filter requests within a time window.
     */
    public function scopeWithinMinutes($query, int $minutes)
    {
        return $query->where('created_at', '>=', now()->subMinutes($minutes));
    }

    /**
     * Scope to filter requests within the last 24 hours.
     */
    public function scopeToday($query)
    {
        return $query->where('created_at', '>=', now()->subHours(24));
    }

    /**
     * Check if request is from a guest.
     */
    public function isGuest(): bool
    {
        return $this->user_id === null;
    }

    /**
     * Get the requester's display name.
     */
    public function getRequesterNameAttribute(): string
    {
        if ($this->user) {
            return $this->user->name;
        }

        if ($this->guest_email) {
            return 'Guest ('.substr($this->guest_email, 0, 3).'...)';
        }

        return 'Anonymous Listener';
    }

    /**
     * Get the status badge color.
     */
    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_PENDING => 'warning',
            self::STATUS_PLAYING => 'primary',
            self::STATUS_PLAYED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
            default => 'secondary',
        };
    }
}
