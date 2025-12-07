<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventReminder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class EventReminderController extends Controller
{
    /**
     * Subscribe to event reminders
     */
    public function subscribe(Request $request, Event $event): JsonResponse
    {
        $validated = $request->validate([
            'email' => ['required', 'email', 'max:255'],
        ]);

        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to subscribe to reminders.',
            ], 401);
        }

        // Check if already subscribed
        $existing = EventReminder::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if ($existing) {
            return response()->json([
                'success' => false,
                'message' => 'You are already subscribed to reminders for this event.',
            ], 422);
        }

        // Create reminder
        EventReminder::create([
            'event_id' => $event->id,
            'user_id' => $user->id,
            'email' => $validated['email'],
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Successfully subscribed! You will receive a reminder email before this event.',
            'subscribed' => true,
        ]);
    }

    /**
     * Unsubscribe from event reminders
     */
    public function unsubscribe(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'You must be logged in to manage reminders.',
            ], 401);
        }

        $reminder = EventReminder::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        if (! $reminder) {
            return response()->json([
                'success' => false,
                'message' => 'You are not subscribed to reminders for this event.',
            ], 422);
        }

        $reminder->delete();

        return response()->json([
            'success' => true,
            'message' => 'Successfully unsubscribed from event reminders.',
            'subscribed' => false,
        ]);
    }

    /**
     * Check reminder subscription status
     */
    public function status(Request $request, Event $event): JsonResponse
    {
        $user = $request->user();

        if (! $user) {
            return response()->json([
                'success' => true,
                'subscribed' => false,
                'email' => null,
            ]);
        }

        $reminder = EventReminder::where('event_id', $event->id)
            ->where('user_id', $user->id)
            ->first();

        return response()->json([
            'success' => true,
            'subscribed' => (bool) $reminder,
            'email' => $reminder?->email,
        ]);
    }
}
