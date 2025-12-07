<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EventReminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'email',
        'reminder_sent',
        'reminder_sent_at',
    ];

    protected function casts(): array
    {
        return [
            'reminder_sent' => 'boolean',
            'reminder_sent_at' => 'datetime',
        ];
    }

    public function event(): BelongsTo
    {
        return $this->belongsTo(Event::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function scopePending($query)
    {
        return $query->where('reminder_sent', false);
    }

    public function scopeSent($query)
    {
        return $query->where('reminder_sent', true);
    }
}
