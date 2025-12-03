<?php

namespace App\DTOs;

use Illuminate\Support\Carbon;

readonly class SongHistoryDTO
{
    public function __construct(
        public int $id,
        public SongDTO $song,
        public Carbon $playedAt,
        public int $duration,
        public ?string $playlist,
        public ?string $dj,
    ) {}

    public static function fromApi(array $data): self
    {
        return new self(
            id: (int) ($data['sh_id'] ?? $data['id'] ?? 0),
            song: SongDTO::fromApi($data['song'] ?? $data),
            playedAt: Carbon::createFromTimestamp($data['played_at'] ?? time()),
            duration: (int) ($data['duration'] ?? $data['song']['duration'] ?? 0),
            playlist: $data['playlist'] ?? null,
            dj: $data['streamer'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'song' => $this->song->toArray(),
            'played_at' => $this->playedAt->toIso8601String(),
            'duration' => $this->duration,
            'playlist' => $this->playlist,
            'dj' => $this->dj,
        ];
    }
}
