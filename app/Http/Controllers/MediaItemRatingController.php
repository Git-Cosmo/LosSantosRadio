<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use App\Models\MediaItemRating;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MediaItemRatingController extends Controller
{
    /**
     * Store or update a rating for a media item.
     */
    public function store(Request $request, MediaItem $mediaItem): RedirectResponse
    {
        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in to rate items.');
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'nullable|string|max:1000',
        ]);

        $rating = MediaItemRating::updateOrCreate(
            [
                'media_item_id' => $mediaItem->id,
                'user_id' => Auth::id(),
            ],
            [
                'rating' => $validated['rating'],
                'review' => $validated['review'] ?? null,
            ]
        );

        // Update media item's average rating
        $mediaItem->updateRatingStats();

        activity()
            ->causedBy(Auth::user())
            ->performedOn($mediaItem)
            ->log('rated media item');

        return back()->with('success', 'Rating submitted successfully!');
    }

    /**
     * Delete a rating.
     */
    public function destroy(MediaItem $mediaItem): RedirectResponse
    {
        if (!Auth::check()) {
            return back()->with('error', 'You must be logged in.');
        }

        $rating = MediaItemRating::where('media_item_id', $mediaItem->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($rating) {
            $rating->delete();
            $mediaItem->updateRatingStats();
            
            return back()->with('success', 'Rating removed successfully!');
        }

        return back()->with('error', 'Rating not found.');
    }
}
