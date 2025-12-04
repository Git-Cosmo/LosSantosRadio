<?php

namespace App\Http\Controllers;

use App\Exceptions\AzuraCastException;
use App\Services\AzuraCastService;

class ScheduleController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast
    ) {}

    /**
     * Display the schedule page with playlists.
     *
     * Fetches playlists from AzuraCast API and displays them
     * in a schedule format, showing which playlists are currently active.
     */
    public function index()
    {
        $schedule = [];
        $playlists = collect();
        $nowPlaying = null;
        $error = null;

        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();

            // Fetch playlists from AzuraCast API
            $playlists = $this->azuraCast->getPlaylists();

            // Build schedule from playlists that have schedule items
            $schedule = $this->buildScheduleFromPlaylists($playlists);
        } catch (AzuraCastException $e) {
            $error = 'Unable to fetch schedule data. Please try again later.';
        }

        return view('schedule.index', [
            'schedule' => $schedule,
            'playlists' => $playlists,
            'nowPlaying' => $nowPlaying,
            'error' => $error,
        ]);
    }

    /**
     * Build a schedule array from playlist data.
     *
     * @param  \Illuminate\Support\Collection  $playlists
     */
    private function buildScheduleFromPlaylists($playlists): array
    {
        $schedule = [];

        foreach ($playlists as $playlist) {
            // Skip disabled playlists and jingles
            if (! $playlist->isEnabled || $playlist->isJingle) {
                continue;
            }

            $formattedSchedule = $playlist->getFormattedSchedule();

            foreach ($formattedSchedule as $item) {
                $schedule[] = [
                    'playlist_id' => $playlist->id,
                    'title' => $playlist->name,
                    'description' => $playlist->type === 'once_per_day'
                        ? 'Plays once daily'
                        : ($playlist->type === 'scheduled' ? 'Scheduled playlist' : 'Regular rotation'),
                    'day' => $item['day'],
                    'day_number' => $item['day_number'],
                    'time' => $item['start_time'].' - '.$item['end_time'],
                    'start_time' => $item['start_time'],
                    'end_time' => $item['end_time'],
                    'is_current' => $playlist->isCurrentlyActive(),
                    'type' => $playlist->type,
                ];
            }
        }

        // Sort by day number, then start time
        usort($schedule, function ($a, $b) {
            if ($a['day_number'] === $b['day_number']) {
                return strcmp($a['start_time'], $b['start_time']);
            }

            return $a['day_number'] - $b['day_number'];
        });

        return $schedule;
    }
}
