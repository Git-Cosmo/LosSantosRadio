<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

readonly class NowPlayingDTO
{
    public function __construct(
        public SongDTO $currentSong,
        public ?SongDTO $nextSong,
        public int $elapsed,
        public int $remaining,
        public int $duration,
        public bool $isLive,
        public int $listeners,
        public int $uniqueListeners,
        public Carbon $playedAt,
        public bool $isOnline,
        public ?string $streamerName,
    ) {}

    public static function fromApi(array $data): self
    {
        $nowPlaying = $data['now_playing'] ?? $data;
        $song = $nowPlaying['song'] ?? $nowPlaying;

        $playingNext = $data['playing_next']['song'] ?? null;

        return new self(
            currentSong: SongDTO::fromApi($song),
            nextSong: $playingNext ? SongDTO::fromApi($playingNext) : null,
            elapsed: (int) ($nowPlaying['elapsed'] ?? 0),
            remaining: (int) ($nowPlaying['remaining'] ?? 0),
            duration: (int) ($nowPlaying['duration'] ?? $song['duration'] ?? 0),
            isLive: (bool) ($data['live']['is_live'] ?? false),
            listeners: (int) ($data['listeners']['current'] ?? $data['listeners']['total'] ?? 0),
            uniqueListeners: (int) ($data['listeners']['unique'] ?? 0),
            playedAt: Carbon::createFromTimestamp($nowPlaying['played_at'] ?? time()),
            isOnline: (bool) ($data['is_online'] ?? true),
            streamerName: $data['live']['streamer_name'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'current_song' => $this->currentSong->toArray(),
            'next_song' => $this->nextSong?->toArray(),
            'elapsed' => $this->elapsed,
            'remaining' => $this->remaining,
            'duration' => $this->duration,
            'is_live' => $this->isLive,
            'listeners' => $this->listeners,
            'unique_listeners' => $this->uniqueListeners,
            'played_at' => $this->playedAt->toIso8601String(),
            'is_online' => $this->isOnline,
            'streamer_name' => $this->streamerName,
        ];
    }

    public function progressPercentage(): float
    {
        if ($this->duration <= 0) {
            return 0;
        }

        return min(100, ($this->elapsed / $this->duration) * 100);
    }
}
