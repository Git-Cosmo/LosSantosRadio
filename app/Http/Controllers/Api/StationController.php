<?php

namespace App\Http\Controllers\Api;

use App\Exceptions\AzuraCastException;
use App\Http\Controllers\Controller;
use App\Models\SongRequest;
use App\Services\AzuraCastService;
use App\Services\RequestLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class StationController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast,
        protected RequestLimitService $requestLimiter
    ) {}

    /**
     * Get station playlists with schedules.
     *
     * API endpoint: GET /api/station/{stationId}/playlists
     *
     * Returns playlists with their schedule items for display on the schedule page.
     */
    public function playlists(int $stationId): JsonResponse
    {
        try {
            $playlists = $this->azuraCast->getPlaylists();
            $data = $playlists->map(fn ($playlist) => $playlist->toArray())->values()->all();

            return response()->json([
                'success' => true,
                'data' => $data,
                'meta' => [
                    'total' => $playlists->count(),
                ],
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'message' => 'Unable to fetch playlists.',
            ], 503);
        }
    }

    /**
     * Get list of requestable songs.
     */
    public function requestableList(Request $request, int $stationId): JsonResponse
    {
        try {
            $perPage = min(100, max(1, (int) $request->input('per_page', 50)));
            $page = max(1, (int) $request->input('page', 1));
            $search = $request->input('searchPhrase');

            $result = $this->azuraCast->getRequestableSongs($perPage, $page, $search);

            return response()->json([
                'success' => true,
                'data' => $result['songs']->map(fn ($song) => $song->toArray())->values()->all(),
                'meta' => [
                    'total' => $result['total'],
                    'page' => $page,
                    'per_page' => $perPage,
                ],
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch requestable songs.',
                'message' => $e->getMessage(),
            ], 503);
        }
    }

    /**
     * Submit a song request.
     *
     * API endpoint: POST /api/station/{stationId}/request/{requestId}
     *
     * Response codes:
     * - 200: Request submitted successfully
     * - 403: Request not allowed (rate limited or requests disabled)
     * - 404: Song not found
     * - 500: Server error
     */
    public function submitRequest(Request $request, int $stationId, string $requestId): JsonResponse
    {
        // Validate optional input fields
        $validated = $request->validate([
            'song_title' => 'nullable|string|max:255',
            'song_artist' => 'nullable|string|max:255',
            'guest_email' => 'nullable|email|max:255',
        ]);

        // Check rate limits
        $canRequest = $this->requestLimiter->canRequest($request);
        if (! $canRequest['allowed']) {
            return response()->json([
                'success' => false,
                'error' => 'Request not allowed.',
                'message' => $canRequest['reason'],
            ], 403);
        }

        try {
            // Submit to AzuraCast
            $result = $this->azuraCast->submitRequest($requestId);

            if (! $result['success']) {
                // Check if it's a "not found" type error
                $message = strtolower($result['message'] ?? '');
                if (str_contains($message, 'not found') || str_contains($message, 'does not exist')) {
                    return response()->json([
                        'success' => false,
                        'error' => 'Song not found.',
                        'message' => $result['message'],
                    ], 404);
                }

                return response()->json([
                    'success' => false,
                    'error' => 'Request failed.',
                    'message' => $result['message'],
                ], 403);
            }

            // Record locally
            $songRequest = SongRequest::create([
                'user_id' => $request->user()?->id,
                'song_id' => $requestId,
                'song_title' => $validated['song_title'] ?? 'Unknown',
                'song_artist' => $validated['song_artist'] ?? 'Unknown',
                'ip_address' => $request->ip(),
                'session_id' => $request->hasSession() ? $request->session()->getId() : null,
                'guest_email' => $validated['guest_email'] ?? null,
                'status' => SongRequest::STATUS_PENDING,
            ]);

            return response()->json([
                'success' => true,
                'message' => $result['message'],
                'request_id' => $songRequest->id,
                'remaining' => max(0, $canRequest['remaining'] - 1),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error.',
                'message' => 'Unable to process request at this time.',
            ], 500);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Server error.',
                'message' => 'An unexpected error occurred.',
            ], 500);
        }
    }
}
