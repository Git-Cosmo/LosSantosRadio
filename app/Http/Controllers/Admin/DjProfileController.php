<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DjProfile;
use App\Models\DjSchedule;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DjProfileController extends Controller
{
    public function index(): View
    {
        $djProfiles = DjProfile::with('user')
            ->orderBy('is_featured', 'desc')
            ->orderBy('stage_name')
            ->paginate(15);

        return view('admin.djs.index', [
            'djProfiles' => $djProfiles,
        ]);
    }

    public function create(): View
    {
        $users = User::whereDoesntHave('djProfile')
            ->orderBy('name')
            ->get();

        return view('admin.djs.create', [
            'users' => $users,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'user_id' => 'required|exists:users,id|unique:dj_profiles,user_id',
            'stage_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'genres' => 'nullable|string',
            'show_name' => 'nullable|string|max:255',
            'show_description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['genres'] = $validated['genres']
            ? array_map('trim', explode(',', $validated['genres']))
            : null;

        DjProfile::create($validated);

        // Update user's DJ status
        User::find($validated['user_id'])->update([
            'is_dj' => true,
            'dj_name' => $validated['stage_name'],
        ]);

        return redirect()->route('admin.djs.index')
            ->with('success', 'DJ profile created successfully!');
    }

    public function edit(DjProfile $dj): View
    {
        $dj->load(['user', 'schedules']);

        return view('admin.djs.edit', [
            'djProfile' => $dj,
        ]);
    }

    public function update(Request $request, DjProfile $dj): RedirectResponse
    {
        $validated = $request->validate([
            'stage_name' => 'required|string|max:255',
            'bio' => 'nullable|string',
            'genres' => 'nullable|string',
            'show_name' => 'nullable|string|max:255',
            'show_description' => 'nullable|string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
        ]);

        $validated['genres'] = $validated['genres']
            ? array_map('trim', explode(',', $validated['genres']))
            : null;

        $dj->update($validated);

        // Update user's DJ name if changed
        $dj->user->update(['dj_name' => $validated['stage_name']]);

        return redirect()->route('admin.djs.index')
            ->with('success', 'DJ profile updated successfully!');
    }

    public function destroy(DjProfile $dj): RedirectResponse
    {
        $user = $dj->user;
        $dj->delete();

        // Remove DJ status from user
        $user->update([
            'is_dj' => false,
            'dj_name' => null,
        ]);

        return redirect()->route('admin.djs.index')
            ->with('success', 'DJ profile deleted successfully!');
    }

    public function schedules(DjProfile $dj): View
    {
        $dj->load('schedules');

        $days = [
            0 => 'Sunday',
            1 => 'Monday',
            2 => 'Tuesday',
            3 => 'Wednesday',
            4 => 'Thursday',
            5 => 'Friday',
            6 => 'Saturday',
        ];

        return view('admin.djs.schedules', [
            'djProfile' => $dj,
            'days' => $days,
        ]);
    }

    public function storeSchedule(Request $request, DjProfile $dj): RedirectResponse
    {
        $validated = $request->validate([
            'day_of_week' => 'required|integer|between:0,6',
            'start_time' => 'required|date_format:H:i',
            'end_time' => 'required|date_format:H:i|after:start_time',
            'show_name' => 'nullable|string|max:255',
        ]);

        $dj->schedules()->create($validated);

        return redirect()->route('admin.djs.schedules', $dj)
            ->with('success', 'Schedule added successfully!');
    }

    public function destroySchedule(DjProfile $dj, DjSchedule $schedule): RedirectResponse
    {
        if ($schedule->dj_profile_id !== $dj->id) {
            abort(404);
        }

        $schedule->delete();

        return redirect()->route('admin.djs.schedules', $dj)
            ->with('success', 'Schedule removed successfully!');
    }
}
