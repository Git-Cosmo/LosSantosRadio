<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Spatie\Activitylog\Models\Activity;

class ActivityLogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Activity::with(['causer', 'subject']);

        if ($search = $request->get('search')) {
            $query->where('description', 'like', "%{$search}%");
        }

        if ($event = $request->get('event')) {
            $query->where('event', $event);
        }

        $activities = $query->latest()->paginate(20);
        $events = Activity::distinct()->pluck('event')->filter()->values();

        return view('admin.activity.index', [
            'activities' => $activities,
            'events' => $events,
        ]);
    }

    public function show(Activity $activity): View
    {
        return view('admin.activity.show', [
            'activity' => $activity->load(['causer', 'subject']),
        ]);
    }
}
