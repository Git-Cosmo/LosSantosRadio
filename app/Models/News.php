<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'slug',
        'excerpt',
        'content',
        'source',
        'source_url',
        'image',
        'author_id',
        'is_published',
        'published_at',
    ];

    /**
     * The attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    /**
     * Boot the model.
     */
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (News $news) {
            if (empty($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
            // Ensure slug is unique
            $originalSlug = $news->slug;
            $counter = 1;
            while (static::where('slug', $news->slug)->exists()) {
                $news->slug = $originalSlug.'-'.$counter++;
            }
        });

        static::updating(function (News $news) {
            if ($news->isDirty('title') && ! $news->isDirty('slug')) {
                $news->slug = Str::slug($news->title);
                // Ensure slug is unique
                $originalSlug = $news->slug;
                $counter = 1;
                while (static::where('slug', $news->slug)->where('id', '!=', $news->id)->exists()) {
                    $news->slug = $originalSlug.'-'.$counter++;
                }
            }
        });
    }

    /**
     * Get the author of the news article.
     */
    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Scope to get only published news.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true)
            ->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Scope to get news from a specific source.
     */
    public function scopeFromSource(Builder $query, string $source): Builder
    {
        return $query->where('source', $source);
    }

    /**
     * Scope to order by published date, latest first.
     */
    public function scopeLatestPublished(Builder $query): Builder
    {
        return $query->orderByDesc('published_at');
    }

    /**
     * Get the news URL.
     */
    public function getUrlAttribute(): string
    {
        return route('news.show', $this->slug);
    }

    /**
     * Check if this news is from an external source.
     */
    public function isExternal(): bool
    {
        return $this->source !== 'manual';
    }

    /**
     * Get a short excerpt from the content if excerpt is not set.
     */
    public function getExcerptTextAttribute(): string
    {
        if ($this->excerpt) {
            return $this->excerpt;
        }

        return Str::limit(strip_tags($this->content), 150);
    }
}
