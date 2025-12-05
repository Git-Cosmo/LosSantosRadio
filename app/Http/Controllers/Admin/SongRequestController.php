<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SongRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SongRequestController extends Controller
{
    public function index(Request $request): View
    {
        $query = SongRequest::with('user');

        if ($search = $request->get('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('song_title', 'like', "%{$search}%")
                    ->orWhere('song_artist', 'like', "%{$search}%");
            });
        }

        if ($status = $request->get('status')) {
            $query->where('status', $status);
        }

        $requests = $query->orderBy('queue_order')->latest()->paginate(20);

        return view('admin.requests.index', [
            'requests' => $requests,
            'statuses' => [
                'pending' => 'Pending',
                'playing' => 'Playing',
                'played' => 'Played',
                'rejected' => 'Rejected',
                'cancelled' => 'Cancelled',
            ],
        ]);
    }

    public function edit(SongRequest $songRequest): View
    {
        return view('admin.requests.edit', [
            'request' => $songRequest->load('user'),
            'statuses' => [
                'pending' => 'Pending',
                'playing' => 'Playing',
                'played' => 'Played',
                'rejected' => 'Rejected',
                'cancelled' => 'Cancelled',
            ],
        ]);
    }

    public function update(Request $request, SongRequest $songRequest): RedirectResponse
    {
        $validated = $request->validate([
            'status' => 'required|in:pending,playing,played,rejected,cancelled',
            'queue_order' => 'nullable|integer|min:0',
        ]);

        $updateData = ['status' => $validated['status']];

        if (isset($validated['queue_order'])) {
            $updateData['queue_order'] = $validated['queue_order'];
        }

        if ($validated['status'] === 'played' && $songRequest->status !== 'played') {
            $updateData['played_at'] = now();
        }

        $songRequest->update($updateData);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Song request updated successfully.');
    }

    public function markPlayed(SongRequest $songRequest): RedirectResponse
    {
        $songRequest->update([
            'status' => 'played',
            'played_at' => now(),
        ]);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Song request marked as played.');
    }

    public function reject(SongRequest $songRequest): RedirectResponse
    {
        $songRequest->update(['status' => 'rejected']);

        return redirect()->route('admin.requests.index')
            ->with('success', 'Song request rejected.');
    }

    public function moveUp(SongRequest $songRequest): RedirectResponse
    {
        $songRequest->moveOrderUp();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Song request moved up.');
    }

    public function moveDown(SongRequest $songRequest): RedirectResponse
    {
        $songRequest->moveOrderDown();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Song request moved down.');
    }

    public function destroy(SongRequest $songRequest): RedirectResponse
    {
        $songRequest->delete();

        return redirect()->route('admin.requests.index')
            ->with('success', 'Song request deleted successfully.');
    }
}
