<?php

namespace App\Models;

use Cmgmyr\Messenger\Traits\Messagable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Activitylog\LogOptions;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, HasRoles, LogsActivity, Messagable, Notifiable;

    /**
     * XP thresholds for each level (cumulative).
     */
    public const LEVEL_THRESHOLDS = [
        1 => 0,
        2 => 100,
        3 => 250,
        4 => 500,
        5 => 1000,
        6 => 2000,
        7 => 3500,
        8 => 5500,
        9 => 8000,
        10 => 12000,
        11 => 17000,
        12 => 23000,
        13 => 30000,
        14 => 40000,
        15 => 52000,
        16 => 66000,
        17 => 82000,
        18 => 100000,
        19 => 125000,
        20 => 160000,
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'xp',
        'level',
        'current_streak',
        'longest_streak',
        'last_activity_date',
        'bio',
        'is_dj',
        'dj_name',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'last_activity_date' => 'date',
            'is_dj' => 'boolean',
        ];
    }

    /**
     * Get the social accounts linked to this user.
     */
    public function socialAccounts(): HasMany
    {
        return $this->hasMany(SocialAccount::class);
    }

    /**
     * Get the song requests made by this user.
     */
    public function songRequests(): HasMany
    {
        return $this->hasMany(SongRequest::class);
    }

    /**
     * Get the achievements earned by this user.
     */
    public function achievements(): BelongsToMany
    {
        return $this->belongsToMany(Achievement::class, 'user_achievements')
            ->withPivot('earned_at')
            ->withTimestamps();
    }

    /**
     * Get the user's XP transactions.
     */
    public function xpTransactions(): HasMany
    {
        return $this->hasMany(XpTransaction::class);
    }

    /**
     * Get the user's DJ profile if they are a DJ.
     */
    public function djProfile(): HasOne
    {
        return $this->hasOne(DjProfile::class);
    }

    /**
     * Get events created by this user.
     */
    public function createdEvents(): HasMany
    {
        return $this->hasMany(Event::class, 'created_by');
    }

    /**
     * Get polls created by this user.
     */
    public function createdPolls(): HasMany
    {
        return $this->hasMany(Poll::class, 'created_by');
    }

    /**
     * Get poll votes by this user.
     */
    public function pollVotes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    /**
     * Get the activity log options for the model.
     */
    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email'])
            ->logOnlyDirty();
    }

    /**
     * Check if user has a specific social provider linked.
     */
    public function hasSocialProvider(string $provider): bool
    {
        return $this->socialAccounts()->where('provider', $provider)->exists();
    }

    /**
     * Get the avatar URL or a default.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }

        // Use a gravatar as fallback
        return 'https://www.gravatar.com/avatar/'.md5(strtolower(trim($this->email))).'?d=mp&s=200';
    }

    /**
     * Add XP to the user and check for level up.
     */
    public function addXp(int $amount, string $reason, ?string $sourceType = null, ?int $sourceId = null): void
    {
        if ($amount <= 0) {
            return;
        }

        $this->xp += $amount;

        // Record the transaction
        $this->xpTransactions()->create([
            'amount' => $amount,
            'reason' => $reason,
            'source_type' => $sourceType,
            'source_id' => $sourceId,
        ]);

        // Check for level up
        $this->checkLevelUp();

        $this->save();
    }

    /**
     * Check and apply level up if XP threshold is met.
     */
    public function checkLevelUp(): void
    {
        $maxLevel = max(array_keys(self::LEVEL_THRESHOLDS));

        foreach (self::LEVEL_THRESHOLDS as $level => $threshold) {
            if ($this->xp >= $threshold && $level > $this->level && $level <= $maxLevel) {
                $this->level = $level;
            }
        }
    }

    /**
     * Get XP needed for next level.
     */
    public function getXpToNextLevelAttribute(): int
    {
        $nextLevel = $this->level + 1;

        if (! isset(self::LEVEL_THRESHOLDS[$nextLevel])) {
            return 0; // Max level reached
        }

        return self::LEVEL_THRESHOLDS[$nextLevel] - $this->xp;
    }

    /**
     * Get percentage progress to next level.
     */
    public function getLevelProgressAttribute(): float
    {
        $currentThreshold = self::LEVEL_THRESHOLDS[$this->level] ?? 0;
        $nextThreshold = self::LEVEL_THRESHOLDS[$this->level + 1] ?? $currentThreshold;

        if ($nextThreshold === $currentThreshold) {
            return 100.0; // Max level
        }

        $progress = (($this->xp - $currentThreshold) / ($nextThreshold - $currentThreshold)) * 100;

        return min(100, max(0, $progress));
    }

    /**
     * Update daily streak.
     */
    public function updateStreak(): void
    {
        $today = now()->toDateString();
        $yesterday = now()->subDay()->toDateString();

        if ($this->last_activity_date === null) {
            $this->current_streak = 1;
        } elseif ($this->last_activity_date->toDateString() === $yesterday) {
            $this->current_streak++;
        } elseif ($this->last_activity_date->toDateString() !== $today) {
            $this->current_streak = 1;
        }

        if ($this->current_streak > $this->longest_streak) {
            $this->longest_streak = $this->current_streak;
        }

        $this->last_activity_date = $today;
        $this->save();
    }

    /**
     * Check if user has a specific achievement.
     */
    public function hasAchievement(string $slug): bool
    {
        return $this->achievements()->where('slug', $slug)->exists();
    }

    /**
     * Award an achievement to the user.
     */
    public function awardAchievement(Achievement $achievement): bool
    {
        if ($this->hasAchievement($achievement->slug)) {
            return false;
        }

        $this->achievements()->attach($achievement->id, [
            'earned_at' => now(),
        ]);

        // Award XP for the achievement
        if ($achievement->xp_reward > 0) {
            $this->addXp(
                $achievement->xp_reward,
                "Achievement unlocked: {$achievement->name}",
                Achievement::class,
                $achievement->id
            );
        }

        return true;
    }
}
