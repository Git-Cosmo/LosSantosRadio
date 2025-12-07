<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Event extends Model
{
    /** @use HasFactory<\Database\Factories\EventFactory> */
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'image',
        'event_type',
        'starts_at',
        'ends_at',
        'location',
        'is_featured',
        'is_published',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('title')
            ->saveSlugsTo('slug');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function likes(): HasMany
    {
        return $this->hasMany(EventLike::class);
    }

    public function reminders(): HasMany
    {
        return $this->hasMany(EventReminder::class);
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true);
    }

    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    public function scopeUpcoming($query)
    {
        return $query->where('starts_at', '>=', now());
    }

    public function scopeOngoing($query)
    {
        return $query->where('starts_at', '<=', now())
            ->where(function ($q) {
                $q->where('ends_at', '>=', now())
                    ->orWhereNull('ends_at');
            });
    }

    public function isUpcoming(): bool
    {
        return $this->starts_at > now();
    }

    public function isOngoing(): bool
    {
        return $this->starts_at <= now() && ($this->ends_at === null || $this->ends_at >= now());
    }

    public function isPast(): bool
    {
        return $this->ends_at !== null && $this->ends_at < now();
    }

    public function likesCount(): int
    {
        return $this->likes()->count();
    }

    public function hasUserLiked(?User $user = null, ?string $ipAddress = null): bool
    {
        if ($user) {
            return $this->likes()->where('user_id', $user->id)->exists();
        }

        if ($ipAddress) {
            return $this->likes()->where('ip_address', $ipAddress)->exists();
        }

        return false;
    }

    public function hasUserSubscribed(?User $user = null): bool
    {
        if ($user) {
            return $this->reminders()->where('user_id', $user->id)->exists();
        }

        return false;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array<string, mixed>
     */
    public function toSearchableArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => strip_tags($this->description ?? ''),
            'location' => $this->location,
            'event_type' => $this->event_type,
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->is_published;
    }
}
