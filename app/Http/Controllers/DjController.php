<?php

namespace App\Http\Controllers;

use App\Models\DjProfile;
use App\Models\DjSchedule;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class DjController extends Controller
{
    public function index(): View
    {
        $djs = DjProfile::with(['user', 'schedules'])
            ->active()
            ->orderBy('is_featured', 'desc')
            ->orderBy('stage_name')
            ->get();

        $featuredDjs = $djs->where('is_featured', true);

        return view('djs.index', [
            'djs' => $djs,
            'featuredDjs' => $featuredDjs,
        ]);
    }

    public function show(DjProfile $dj): View
    {
        $dj->load(['user', 'schedules']);

        return view('djs.show', [
            'dj' => $dj,
        ]);
    }

    public function schedule(): View
    {
        $schedules = DjSchedule::with('djProfile')
            ->active()
            ->orderBy('day_of_week')
            ->orderBy('start_time')
            ->get()
            ->groupBy('day_of_week');

        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('djs.schedule', [
            'schedules' => $schedules,
            'days' => $days,
            'currentDay' => Carbon::now()->dayOfWeek,
        ]);
    }

    public function onAir(): JsonResponse
    {
        $now = Carbon::now();
        $currentDay = $now->dayOfWeek;

        $onAirSchedule = DjSchedule::with('djProfile')
            ->active()
            ->forDay($currentDay)
            ->get()
            ->first(fn ($schedule) => $schedule->isLiveNow());

        if ($onAirSchedule) {
            return response()->json([
                'success' => true,
                'on_air' => true,
                'dj' => [
                    'id' => $onAirSchedule->djProfile->id,
                    'stage_name' => $onAirSchedule->djProfile->stage_name,
                    'avatar' => $onAirSchedule->djProfile->avatar_url,
                    'show_name' => $onAirSchedule->show_name ?? $onAirSchedule->djProfile->show_name,
                ],
                'schedule' => [
                    'start_time' => Carbon::parse($onAirSchedule->start_time)->format('g:i A'),
                    'end_time' => Carbon::parse($onAirSchedule->end_time)->format('g:i A'),
                ],
            ]);
        }

        return response()->json([
            'success' => true,
            'on_air' => false,
            'message' => 'AutoDJ is currently playing',
        ]);
    }
}
