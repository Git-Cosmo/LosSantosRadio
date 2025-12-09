<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    public function dashboard(): View
    {
        $settings = Setting::allAsArray();

        return view('admin.settings.dashboard', [
            'settings' => $settings,
        ]);
    }

    public function updateAll(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'settings' => 'required|array',
            'settings.*' => 'nullable',
        ]);

        foreach ($validated['settings'] as $key => $value) {
            // Handle checkbox values (convert to boolean)
            if (in_array($key, ['enable_comments', 'enable_song_requests', 'enable_polls', 'maintenance_mode'])) {
                $value = (bool) $value;
            }

            // Handle numeric values
            if (in_array($key, ['default_station_id', 'listener_update_interval', 'guest_request_limit', 'user_request_limit', 'guest_lyrics_limit'])) {
                $value = (int) $value;
            }

            Setting::set($key, $value);
        }

        // Clear all settings cache
        Setting::clearCache();

        return redirect()->route('admin.settings.dashboard')
            ->with('success', 'Settings updated successfully!');
    }

    public function index(Request $request): View
    {
        $query = Setting::query();

        if ($search = $request->get('search')) {
            $query->where('key', 'like', "%{$search}%");
        }

        if ($group = $request->get('group')) {
            $query->where('group', $group);
        }

        $settings = $query->orderBy('group')->orderBy('key')->paginate(20);
        $groups = Setting::distinct()->pluck('group')->filter()->values();

        return view('admin.settings.index', [
            'settings' => $settings,
            'groups' => $groups,
        ]);
    }

    public function create(): View
    {
        return view('admin.settings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'key' => 'required|string|max:255|unique:settings',
            'value' => 'required|string',
            'type' => 'required|in:string,integer,boolean,json',
            'group' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Setting::create($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting created successfully.');
    }

    public function edit(Setting $setting): View
    {
        return view('admin.settings.edit', [
            'setting' => $setting,
        ]);
    }

    public function update(Request $request, Setting $setting): RedirectResponse
    {
        $validated = $request->validate([
            'value' => 'required|string',
            'type' => 'required|in:string,integer,boolean,json',
            'group' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $setting->update($validated);

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting updated successfully.');
    }

    public function destroy(Setting $setting): RedirectResponse
    {
        $setting->delete();

        return redirect()->route('admin.settings.index')
            ->with('success', 'Setting deleted successfully.');
    }

    public function theme(): View
    {
        $activeTheme = Setting::get('site_theme', 'none');

        return view('admin.theme', [
            'activeTheme' => $activeTheme,
        ]);
    }

    public function updateTheme(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'theme' => 'required|in:none,christmas,newyear',
        ]);

        Setting::set('site_theme', $validated['theme']);

        return redirect()->route('admin.theme')
            ->with('success', 'Theme updated successfully. Changes are now live!');
    }
}
