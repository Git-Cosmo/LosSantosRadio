<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\View\View;

class EventsController extends Controller
{
    public function index(): View
    {
        $upcomingEvents = Event::published()
            ->upcoming()
            ->orderBy('starts_at')
            ->take(10)
            ->get();

        $ongoingEvents = Event::published()
            ->ongoing()
            ->orderBy('starts_at')
            ->get();

        $featuredEvents = Event::published()
            ->featured()
            ->upcoming()
            ->take(3)
            ->get();

        return view('events.index', [
            'upcomingEvents' => $upcomingEvents,
            'ongoingEvents' => $ongoingEvents,
            'featuredEvents' => $featuredEvents,
        ]);
    }

    public function show(string $slug): View
    {
        $event = Event::published()
            ->where('slug', $slug)
            ->firstOrFail();

        return view('events.show', [
            'event' => $event,
        ]);
    }
}
