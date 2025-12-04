<?php

namespace App\DTOs;

readonly class SongDTO
{
    public function __construct(
        public string $id,
        public string $title,
        public string $artist,
        public ?string $album,
        public ?string $art,
        public ?string $lyrics,
        public ?int $duration,
        public ?bool $isRequestable,
        public ?string $uniqueId,
        public ?string $genre,
        public ?string $isrc,
    ) {}

    public static function fromApi(array $data): self
    {
        return new self(
            id: $data['id'] ?? $data['song_id'] ?? '',
            title: $data['title'] ?? $data['text'] ?? 'Unknown Title',
            artist: $data['artist'] ?? 'Unknown Artist',
            album: $data['album'] ?? null,
            art: $data['art'] ?? null,
            lyrics: $data['lyrics'] ?? null,
            duration: isset($data['duration']) ? (int) $data['duration'] : null,
            isRequestable: $data['song_request_enabled'] ?? $data['is_requestable'] ?? null,
            uniqueId: $data['unique_id'] ?? null,
            genre: $data['genre'] ?? null,
            isrc: $data['isrc'] ?? null,
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'artist' => $this->artist,
            'album' => $this->album,
            'art' => $this->art,
            'lyrics' => $this->lyrics,
            'duration' => $this->duration,
            'is_requestable' => $this->isRequestable,
            'unique_id' => $this->uniqueId,
            'genre' => $this->genre,
            'isrc' => $this->isrc,
        ];
    }
}
