<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Analytic extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'ip_address',
        'country_code',
        'country_name',
        'device_type',
        'browser',
        'platform',
        'page_url',
        'referrer',
        'last_activity_at',
    ];

    protected $casts = [
        'last_activity_at' => 'datetime',
    ];

    /**
     * Get the user associated with this analytics record.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope to get active sessions (within last 30 minutes).
     */
    public function scopeActive($query)
    {
        return $query->where('last_activity_at', '>=', now()->subMinutes(30));
    }

    /**
     * Scope to get records by date range.
     */
    public function scopeBetweenDates($query, $startDate, $endDate)
    {
        return $query->whereBetween('created_at', [$startDate, $endDate]);
    }

    /**
     * Scope to get records by country.
     */
    public function scopeByCountry($query, $countryCode)
    {
        return $query->where('country_code', $countryCode);
    }

    /**
     * Scope to get records by device type.
     */
    public function scopeByDevice($query, $deviceType)
    {
        return $query->where('device_type', $deviceType);
    }
}
