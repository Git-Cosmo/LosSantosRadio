<?php

namespace App\Events;

use App\DTOs\NowPlayingDTO;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NowPlayingUpdated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public NowPlayingDTO $nowPlaying;

    public int $stationId;

    /**
     * Create a new event instance.
     */
    public function __construct(NowPlayingDTO $nowPlaying, int $stationId)
    {
        $this->nowPlaying = $nowPlaying;
        $this->stationId = $stationId;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel("radio.station.{$this->stationId}"),
        ];
    }

    /**
     * The event's broadcast name.
     */
    public function broadcastAs(): string
    {
        return 'now-playing.updated';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        return [
            'station_id' => $this->stationId,
            'now_playing' => $this->nowPlaying->toArray(),
            'timestamp' => now()->timestamp,
        ];
    }
}
