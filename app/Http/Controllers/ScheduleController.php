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
     * Display the schedule page.
     */
    public function index()
    {
        $schedule = [];
        $nowPlaying = null;
        $error = null;

        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();
            // Note: AzuraCast schedule API would be called here if configured
            // For now we'll show the current show and indicate AutoDJ
        } catch (AzuraCastException $e) {
            $error = 'Unable to fetch schedule data. Please try again later.';
        }

        return view('schedule.index', [
            'schedule' => $schedule,
            'nowPlaying' => $nowPlaying,
            'error' => $error,
        ]);
    }
}
