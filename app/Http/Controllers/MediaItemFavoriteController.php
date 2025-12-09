<?php

namespace App\Http\Controllers;

use App\Models\MediaItem;
use App\Models\MediaItemFavorite;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class MediaItemFavoriteController extends Controller
{
    /**
     * Toggle favorite status for a media item.
     */
    public function toggle(MediaItem $mediaItem): RedirectResponse|JsonResponse
    {
        if (!Auth::check()) {
            if (request()->wantsJson()) {
                return response()->json(['error' => 'Unauthenticated'], 401);
            }
            return back()->with('error', 'You must be logged in to favorite items.');
        }

        $favorite = MediaItemFavorite::where('media_item_id', $mediaItem->id)
            ->where('user_id', Auth::id())
            ->first();

        if ($favorite) {
            $favorite->delete();
            $isFavorited = false;
            $message = 'Removed from favorites';
        } else {
            MediaItemFavorite::create([
                'media_item_id' => $mediaItem->id,
                'user_id' => Auth::id(),
            ]);
            $isFavorited = true;
            $message = 'Added to favorites';

            activity()
                ->causedBy(Auth::user())
                ->performedOn($mediaItem)
                ->log('favorited media item');
        }

        $favoritesCount = $mediaItem->favorites()->count();

        if (request()->wantsJson()) {
            return response()->json([
                'success' => true,
                'is_favorited' => $isFavorited,
                'favorites_count' => $favoritesCount,
                'message' => $message,
            ]);
        }

        return back()->with('success', $message);
    }

    /**
     * Get user's favorited items.
     */
    public function index()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        $favorites = MediaItem::whereHas('favorites', function ($query) {
            $query->where('user_id', Auth::id());
        })
            ->with(['category', 'subcategory'])
            ->withCount('favorites')
            ->latest()
            ->paginate(20);

        return view('media.favorites', compact('favorites'));
    }
}
