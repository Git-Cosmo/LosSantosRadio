<?php

namespace App\Http\Controllers;

use App\Exceptions\AzuraCastException;
use App\Models\SongRequest;
use App\Services\AzuraCastService;
use App\Services\RequestLimitService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SongRequestController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast,
        protected RequestLimitService $requestLimiter
    ) {}

    /**
     * Display the song request page with library.
     */
    public function index(Request $request)
    {
        $search = $request->input('search');
        $page = max(1, (int) $request->input('page', 1));
        $perPage = 50; // Increased from 25 to show more songs per page

        try {
            $result = $this->azuraCast->getRequestableSongs($perPage, $page, $search);
            $queue = $this->azuraCast->getRequestQueue();
            $canRequest = $this->requestLimiter->canRequest($request);
        } catch (AzuraCastException $e) {
            return view('requests.index', [
                'error' => 'Unable to load song library. Please try again later.',
                'songs' => collect(),
                'queue' => collect(),
                'canRequest' => ['allowed' => false, 'reason' => 'Service unavailable'],
                'search' => $search,
                'total' => 0,
                'page' => 1,
                'perPage' => $perPage,
                'totalPages' => 0,
            ]);
        }

        $total = $result['total'] ?? 0;
        $totalPages = $total > 0 ? (int) ceil($total / $perPage) : 0;

        return view('requests.index', [
            'songs' => $result['songs'],
            'queue' => $queue,
            'canRequest' => $canRequest,
            'search' => $search,
            'page' => $page,
            'perPage' => $perPage,
            'total' => $total,
            'totalPages' => $totalPages,
        ]);
    }

    /**
     * Search the song library (AJAX).
     */
    public function search(Request $request): JsonResponse
    {
        $query = $request->input('q', '');
        $page = (int) $request->input('page', 1);

        try {
            $result = $this->azuraCast->getRequestableSongs(25, $page, $query);

            return response()->json([
                'success' => true,
                'songs' => $result['songs']->map(fn ($song) => $song->toArray())->values()->all(),
                'page' => $page,
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to search songs.',
            ], 503);
        }
    }

    /**
     * Submit a song request.
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'song_id' => 'required|string',
            'song_title' => 'required|string|max:255',
            'song_artist' => 'required|string|max:255',
            'guest_email' => 'nullable|email|max:255',
        ]);

        // Check rate limits
        $canRequest = $this->requestLimiter->canRequest($request);
        if (! $canRequest['allowed']) {
            return response()->json([
                'success' => false,
                'error' => $canRequest['reason'],
            ], 429);
        }

        try {
            // Submit to AzuraCast
            $result = $this->azuraCast->submitRequest($request->input('song_id'));

            if (! $result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $result['message'],
                ], 400);
            }

            // Record locally
            $songRequest = SongRequest::create([
                'user_id' => $request->user()?->id,
                'song_id' => $request->input('song_id'),
                'song_title' => $request->input('song_title'),
                'song_artist' => $request->input('song_artist'),
                'ip_address' => $request->ip(),
                'session_id' => $request->session()->getId(),
                'guest_email' => $request->input('guest_email'),
                'status' => SongRequest::STATUS_PENDING,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Your request has been submitted!',
                'request_id' => $songRequest->id,
                'remaining' => $canRequest['remaining'] - 1,
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to submit request. Please try again.',
            ], 503);
        }
    }

    /**
     * Get the current request queue (AJAX).
     */
    public function queue(): JsonResponse
    {
        try {
            $queue = $this->azuraCast->getRequestQueue();

            return response()->json([
                'success' => true,
                'queue' => $queue->map(fn ($song) => $song->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch request queue.',
            ], 503);
        }
    }

    /**
     * Check request limits for current user/guest.
     */
    public function checkLimits(Request $request): JsonResponse
    {
        $canRequest = $this->requestLimiter->canRequest($request);

        return response()->json($canRequest);
    }

    /**
     * Get the user's request history.
     */
    public function history(Request $request)
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $requests = SongRequest::byUser($user)
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return view('requests.history', [
            'requests' => $requests,
        ]);
    }
}
