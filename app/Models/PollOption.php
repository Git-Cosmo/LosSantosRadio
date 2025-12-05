<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PollOption extends Model
{
    protected $fillable = [
        'poll_id',
        'option_text',
        'image',
        'sort_order',
    ];

    public function poll(): BelongsTo
    {
        return $this->belongsTo(Poll::class);
    }

    public function votes(): HasMany
    {
        return $this->hasMany(PollVote::class);
    }

    public function voteCount(): int
    {
        return $this->votes()->count();
    }

    public function votePercentage(): float
    {
        $totalVotes = $this->poll->totalVotes();

        if ($totalVotes === 0) {
            return 0;
        }

        return round(($this->voteCount() / $totalVotes) * 100, 1);
    }
}
