<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DjSchedule extends Model
{
    protected $fillable = [
        'dj_profile_id',
        'day_of_week',
        'start_time',
        'end_time',
        'show_name',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'start_time' => 'datetime:H:i',
            'end_time' => 'datetime:H:i',
            'is_active' => 'boolean',
        ];
    }

    public function djProfile(): BelongsTo
    {
        return $this->belongsTo(DjProfile::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForDay($query, int $dayOfWeek)
    {
        return $query->where('day_of_week', $dayOfWeek);
    }

    public function getDayNameAttribute(): string
    {
        $days = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];

        return $days[$this->day_of_week] ?? '';
    }

    public function getFormattedTimeAttribute(): string
    {
        $start = Carbon::parse($this->start_time)->format('g:i A');
        $end = Carbon::parse($this->end_time)->format('g:i A');

        return "{$start} - {$end}";
    }

    public function isLiveNow(): bool
    {
        $now = Carbon::now();
        $currentDay = $now->dayOfWeek;

        if ($this->day_of_week !== $currentDay) {
            return false;
        }

        $currentTime = $now->format('H:i:s');
        $startTime = Carbon::parse($this->start_time)->format('H:i:s');
        $endTime = Carbon::parse($this->end_time)->format('H:i:s');

        return $currentTime >= $startTime && $currentTime <= $endTime;
    }
}
