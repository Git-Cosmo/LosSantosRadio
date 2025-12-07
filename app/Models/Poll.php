<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravel\Scout\Searchable;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Poll extends Model
{
    /** @use HasFactory<\Database\Factories\PollFactory> */
    use HasFactory, HasSlug, Searchable;

    protected $fillable = [
        'question',
        'slug',
        'description',
        'starts_at',
        'ends_at',
        'allow_multiple',
        'is_active',
        'show_results',
        'created_by',
    ];

    protected function casts(): array
    {
        return [
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'allow_multiple' => 'boolean',
            'is_active' => 'boolean',
            'show_results' => 'boolean',
        ];
    }

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('question')
            ->saveSlugsTo('slug');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function options(): HasMany
    {
        return $this->hasMany(PollOption::class)->orderBy('sort_order');
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true)
            ->where('starts_at', '<=', now())
            ->where('ends_at', '>=', now());
    }

    public function isOpen(): bool
    {
        return $this->is_active &&
            $this->starts_at <= now() &&
            $this->ends_at >= now();
    }

    public function hasEnded(): bool
    {
        return $this->ends_at < now();
    }

    public function totalVotes(): int
    {
        return $this->votes()->count();
    }

    public function hasUserVoted(?User $user = null, ?string $ipAddress = null): bool
    {
        if ($user) {
            return $this->votes()->where('user_id', $user->id)->exists();
        }

        if ($ipAddress) {
            return $this->votes()->where('ip_address', $ipAddress)->exists();
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
            'question' => $this->question,
            'description' => strip_tags($this->description ?? ''),
        ];
    }

    /**
     * Determine if the model should be searchable.
     */
    public function shouldBeSearchable(): bool
    {
        return $this->is_active;
    }
}
