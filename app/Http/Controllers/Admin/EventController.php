<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class EventController extends Controller
{
    public function index(): View
    {
        $events = Event::with('creator')
            ->orderBy('starts_at', 'desc')
            ->paginate(15);

        return view('admin.events.index', [
            'events' => $events,
        ]);
    }

    public function create(): View
    {
        return view('admin.events.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_type' => 'required|string|in:general,special,live_show,contest,meetup',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $validated['created_by'] = auth()->id();

        Event::create($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully!');
    }

    public function edit(Event $event): View
    {
        return view('admin.events.edit', [
            'event' => $event,
        ]);
    }

    public function update(Request $request, Event $event): RedirectResponse
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'event_type' => 'required|string|in:general,special,live_show,contest,meetup',
            'starts_at' => 'required|date',
            'ends_at' => 'nullable|date|after:starts_at',
            'location' => 'nullable|string|max:255',
            'is_featured' => 'boolean',
            'is_published' => 'boolean',
        ]);

        $event->update($validated);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully!');
    }

    public function destroy(Event $event): RedirectResponse
    {
        $event->delete();

        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully!');
    }
}
