<?php

namespace App\DTOs;

readonly class StationDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public string $shortcode,
        public ?string $description,
        public ?string $url,
        public ?string $listenUrl,
        public ?string $publicPlaylistUri,
        public bool $isOnline,
        public bool $enableRequests,
        public int $requestDelay,
        public int $requestThreshold,
    ) {}

    public static function fromApi(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            name: $data['name'] ?? 'Unknown Station',
            shortcode: $data['shortcode'] ?? 'unknown',
            description: $data['description'] ?? null,
            url: $data['url'] ?? null,
            listenUrl: $data['listen_url'] ?? null,
            publicPlaylistUri: $data['public_player_url'] ?? null,
            isOnline: (bool) ($data['is_online'] ?? false),
            // Support both 'requests_enabled' (official API) and 'enable_requests' (legacy)
            enableRequests: (bool) ($data['requests_enabled'] ?? $data['enable_requests'] ?? false),
            requestDelay: (int) ($data['request_delay'] ?? 0),
            requestThreshold: (int) ($data['request_threshold'] ?? 0),
        );
    }

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'shortcode' => $this->shortcode,
            'description' => $this->description,
            'url' => $this->url,
            'listen_url' => $this->listenUrl,
            'public_playlist_uri' => $this->publicPlaylistUri,
            'is_online' => $this->isOnline,
            'enable_requests' => $this->enableRequests,
            'request_delay' => $this->requestDelay,
            'request_threshold' => $this->requestThreshold,
        ];
    }
}
