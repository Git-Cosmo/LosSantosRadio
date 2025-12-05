<?php

namespace App\Http\Controllers;

use App\Exceptions\AzuraCastException;
use App\Services\AzuraCastService;
use Illuminate\Http\JsonResponse;

class PlaylistsController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast
    ) {}

    /**
     * Get station playlists as JSON.
     */
    public function index(): JsonResponse
    {
        try {
            $playlists = $this->azuraCast->getPlaylists();

            return response()->json([
                'success' => true,
                'data' => $playlists->map(fn ($playlist) => $playlist->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch playlists data.',
            ], 503);
        }
    }

    /**
     * Get active playlists (enabled and non-jingle).
     */
    public function active(): JsonResponse
    {
        try {
            $playlists = $this->azuraCast->getPlaylists()
                ->filter(fn ($playlist) => $playlist->isEnabled && ! $playlist->isJingle);

            return response()->json([
                'success' => true,
                'data' => $playlists->map(fn ($playlist) => $playlist->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch playlists data.',
            ], 503);
        }
    }

    /**
     * Get currently active playlists (those scheduled for now).
     */
    public function current(): JsonResponse
    {
        try {
            $playlists = $this->azuraCast->getPlaylists()
                ->filter(fn ($playlist) => $playlist->isCurrentlyActive());

            return response()->json([
                'success' => true,
                'data' => $playlists->map(fn ($playlist) => $playlist->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch playlists data.',
            ], 503);
        }
    }
}
