<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RadioServer extends Model
{
    protected $fillable = [
        'name',
        'type',
        'host',
        'port',
        'mount_point',
        'stream_id',
        'admin_user',
        'admin_password',
        'ssl',
        'is_active',
        'auto_start',
        'docker_host',
        'docker_container_name',
        'docker_image',
        'docker_env',
        'docker_ports',
        'status',
        'last_check_at',
        'last_error',
    ];

    protected $casts = [
        'ssl' => 'boolean',
        'is_active' => 'boolean',
        'auto_start' => 'boolean',
        'docker_env' => 'array',
        'docker_ports' => 'array',
        'last_check_at' => 'datetime',
        'port' => 'integer',
        'stream_id' => 'integer',
    ];

    protected $hidden = [
        'admin_password',
    ];

    /**
     * Check if server is Icecast
     */
    public function isIcecast(): bool
    {
        return $this->type === 'icecast';
    }

    /**
     * Check if server is Shoutcast
     */
    public function isShoutcast(): bool
    {
        return $this->type === 'shoutcast';
    }

    /**
     * Check if server is running
     */
    public function isRunning(): bool
    {
        return $this->status === 'running';
    }

    /**
     * Get the stream URL
     */
    public function getStreamUrlAttribute(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';
        $url = "{$protocol}://{$this->host}:{$this->port}";

        if ($this->isIcecast() && $this->mount_point) {
            $url .= $this->mount_point;
        } elseif ($this->isShoutcast() && $this->stream_id) {
            $url .= "/stream/{$this->stream_id}";
        }

        return $url;
    }

    /**
     * Get the admin URL
     */
    public function getAdminUrlAttribute(): string
    {
        $protocol = $this->ssl ? 'https' : 'http';
        return "{$protocol}://{$this->host}:{$this->port}/admin";
    }

    /**
     * Mark server as running
     */
    public function markAsRunning(): void
    {
        $this->update([
            'status' => 'running',
            'last_check_at' => now(),
            'last_error' => null,
        ]);
    }

    /**
     * Mark server as stopped
     */
    public function markAsStopped(?string $error = null): void
    {
        $this->update([
            'status' => 'stopped',
            'last_check_at' => now(),
            'last_error' => $error,
        ]);
    }

    /**
     * Mark server as error
     */
    public function markAsError(string $error): void
    {
        $this->update([
            'status' => 'error',
            'last_check_at' => now(),
            'last_error' => $error,
        ]);
    }
}
