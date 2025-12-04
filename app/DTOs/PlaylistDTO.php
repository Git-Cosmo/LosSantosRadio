<?php

namespace App\DTOs;

use Carbon\Carbon;

/**
 * Data Transfer Object for AzuraCast Playlist data.
 *
 * @see https://www.azuracast.com/docs/developers/apis/
 */
readonly class PlaylistDTO
{
    public function __construct(
        public int $id,
        public string $name,
        public ?string $shortName,
        public string $type,
        public string $source,
        public int $order,
        public bool $isEnabled,
        public bool $isJingle,
        public ?int $weight,
        public ?array $scheduleItems,
        public ?string $playOnceTime,
        public ?int $playPerMinutes,
        public ?int $playPerSongs,
        public ?int $playPerHourMinute,
    ) {}

    /**
     * Create a PlaylistDTO from AzuraCast API response data.
     */
    public static function fromApi(array $data): self
    {
        return new self(
            id: (int) ($data['id'] ?? 0),
            name: $data['name'] ?? 'Unknown Playlist',
            shortName: $data['short_name'] ?? null,
            type: $data['type'] ?? 'default',
            source: $data['source'] ?? 'songs',
            order: (int) ($data['order'] ?? 0),
            isEnabled: (bool) ($data['is_enabled'] ?? false),
            isJingle: (bool) ($data['is_jingle'] ?? false),
            weight: isset($data['weight']) ? (int) $data['weight'] : null,
            scheduleItems: $data['schedule_items'] ?? null,
            playOnceTime: $data['play_once_time'] ?? null,
            playPerMinutes: isset($data['play_per_minutes']) ? (int) $data['play_per_minutes'] : null,
            playPerSongs: isset($data['play_per_songs']) ? (int) $data['play_per_songs'] : null,
            playPerHourMinute: isset($data['play_per_hour_minute']) ? (int) $data['play_per_hour_minute'] : null,
        );
    }

    /**
     * Get the formatted schedule for this playlist.
     *
     * @return array<int, array{day: string, start_time: string, end_time: string}>
     */
    public function getFormattedSchedule(): array
    {
        if (empty($this->scheduleItems)) {
            return [];
        }

        $dayNames = ['Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday'];
        $formatted = [];

        foreach ($this->scheduleItems as $item) {
            $startTime = $item['start_time'] ?? null;
            $endTime = $item['end_time'] ?? null;
            $days = $item['days'] ?? [];

            if (! $startTime || ! $endTime) {
                continue;
            }

            foreach ($days as $day) {
                // Validate day is within valid range (0-6)
                // Use is_numeric() since JSON may deserialize integers as strings
                $day = is_numeric($day) ? (int) $day : -1;
                if ($day < 0 || $day > 6) {
                    continue;
                }

                $formatted[] = [
                    'day' => $dayNames[$day],
                    'day_number' => $day,
                    'start_time' => $this->formatTime($startTime),
                    'end_time' => $this->formatTime($endTime),
                    'start_raw' => $startTime,
                    'end_raw' => $endTime,
                ];
            }
        }

        // Sort by day number, then start time
        usort($formatted, function ($a, $b) {
            if ($a['day_number'] === $b['day_number']) {
                return strcmp($a['start_raw'], $b['start_raw']);
            }

            return $a['day_number'] - $b['day_number'];
        });

        return $formatted;
    }

    /**
     * Check if this playlist is currently active based on schedule.
     */
    public function isCurrentlyActive(): bool
    {
        if (! $this->isEnabled) {
            return false;
        }

        if (empty($this->scheduleItems)) {
            // No schedule means always active (if enabled)
            return true;
        }

        $now = Carbon::now();
        $currentDay = (int) $now->format('w'); // 0=Sunday, 6=Saturday
        $currentTime = $now->format('Hi'); // HHMM format

        foreach ($this->scheduleItems as $item) {
            $days = $item['days'] ?? [];
            $startTime = $item['start_time'] ?? null;
            $endTime = $item['end_time'] ?? null;

            if (! in_array($currentDay, $days)) {
                continue;
            }

            if ($startTime && $endTime) {
                // Handle overnight schedules (e.g., 2300-0200)
                if ($startTime > $endTime) {
                    if ($currentTime >= $startTime || $currentTime <= $endTime) {
                        return true;
                    }
                } else {
                    if ($currentTime >= $startTime && $currentTime <= $endTime) {
                        return true;
                    }
                }
            }
        }

        return false;
    }

    /**
     * Format time from HHMM to H:MM AM/PM.
     *
     * @param  string  $time  Time in HHMM format (e.g., "0800", "1430")
     * @return string Formatted time (e.g., "8:00 AM", "2:30 PM")
     */
    private function formatTime(string $time): string
    {
        // Remove any non-numeric characters and validate
        $time = preg_replace('/[^0-9]/', '', $time);

        if (empty($time) || ! ctype_digit($time)) {
            return '12:00 AM';
        }

        if (strlen($time) < 4) {
            $time = str_pad($time, 4, '0', STR_PAD_LEFT);
        }

        $hours = (int) substr($time, 0, 2);
        $minutes = substr($time, 2, 2);

        // Validate hours and minutes
        if ($hours > 23) {
            $hours = 0;
        }
        if ((int) $minutes > 59) {
            $minutes = '00';
        }

        $period = $hours >= 12 ? 'PM' : 'AM';
        $hours = $hours % 12 ?: 12;

        return sprintf('%d:%s %s', $hours, $minutes, $period);
    }

    /**
     * Convert the DTO to an array.
     */
    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'short_name' => $this->shortName,
            'type' => $this->type,
            'source' => $this->source,
            'order' => $this->order,
            'is_enabled' => $this->isEnabled,
            'is_jingle' => $this->isJingle,
            'weight' => $this->weight,
            'schedule_items' => $this->scheduleItems,
            'formatted_schedule' => $this->getFormattedSchedule(),
            'is_currently_active' => $this->isCurrentlyActive(),
        ];
    }
}
