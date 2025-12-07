<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventLike;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventLikeController extends Controller
{
    /**
     * Toggle like for an event (like/unlike)
     */
    public function toggle(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();
        $ipAddress = $request->ip();

        // Find existing like
        $existingLike = EventLike::where('event_id', $event->id)
            ->where(function ($query) use ($user, $ipAddress) {
                if ($user) {
                    $query->where('user_id', $user->id);
                } else {
                    $query->where('ip_address', $ipAddress);
                }
            })
            ->first();

        if ($existingLike) {
            // Unlike
            $existingLike->delete();
            $liked = false;
        } else {
            // Like
            EventLike::create([
                'event_id' => $event->id,
                'user_id' => $user?->id,
                'ip_address' => $user ? null : $ipAddress,
            ]);
            $liked = true;
        }

        // Get fresh count directly from database
        $likesCount = EventLike::where('event_id', $event->id)->count();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }

    /**
     * Get like status and count for an event
     */
    public function status(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();
        $ipAddress = $request->ip();

        $liked = $event->hasUserLiked($user, $ipAddress);
        $likesCount = $event->likesCount();

        return response()->json([
            'success' => true,
            'liked' => $liked,
            'likes_count' => $likesCount,
        ]);
    }
}
