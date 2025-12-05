<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DiscordLog extends Model
{
    protected $fillable = [
        'type',
        'action',
        'message',
        'data',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    /**
     * Scope to logs of a specific type.
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Create an info log.
     */
    public static function info(string $action, string $message, ?array $data = null): self
    {
        return static::create([
            'type' => 'info',
            'action' => $action,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create a warning log.
     */
    public static function warning(string $action, string $message, ?array $data = null): self
    {
        return static::create([
            'type' => 'warning',
            'action' => $action,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create an error log.
     */
    public static function error(string $action, string $message, ?array $data = null): self
    {
        return static::create([
            'type' => 'error',
            'action' => $action,
            'message' => $message,
            'data' => $data,
        ]);
    }

    /**
     * Create a sync log.
     */
    public static function sync(string $action, string $message, ?array $data = null): self
    {
        return static::create([
            'type' => 'sync',
            'action' => $action,
            'message' => $message,
            'data' => $data,
        ]);
    }
}
