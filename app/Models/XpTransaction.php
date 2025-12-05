<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class XpTransaction extends Model
{
    protected $fillable = [
        'user_id',
        'amount',
        'reason',
        'source_type',
        'source_id',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function source()
    {
        if ($this->source_type && $this->source_id) {
            return $this->morphTo('source', 'source_type', 'source_id');
        }

        return null;
    }
}
