<?php

namespace App\Http\Controllers;

use App\Exceptions\AzuraCastException;
use App\Services\AzuraCastService;
use Illuminate\Http\Request;

class SongsController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast
    ) {}

    /**
     * Display the songs page with pagination.
     */
    public function index(Request $request)
    {
        $nowPlaying = null;
        $history = collect();
        $error = null;
        $search = $request->query('search', '');
        $page = max(1, (int) $request->query('page', 1));
        $perPage = 12;
        $songs = collect();
        $totalSongs = 0;
        $totalPages = 1;

        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();
            $history = $this->azuraCast->getHistory(20);

            // Fetch paginated song library
            $libraryData = $this->azuraCast->getRequestableSongs($perPage, $page, $search === '' ? null : $search);
            $songs = $libraryData['songs'];
            $totalSongs = $libraryData['total'];
            $totalPages = max(1, (int) ceil($totalSongs / $perPage));

            // Ensure page is within valid range
            if ($page > $totalPages) {
                return redirect()->route('songs', array_filter(['page' => $totalPages, 'search' => $search ?: null]));
            }
        } catch (AzuraCastException $e) {
            $error = 'Unable to fetch song data. Please try again later.';
        }

        return view('songs.index', [
            'nowPlaying' => $nowPlaying,
            'history' => $history,
            'error' => $error,
            'search' => $search,
            'songs' => $songs,
            'totalSongs' => $totalSongs,
            'currentPage' => $page,
            'totalPages' => $totalPages,
        ]);
    }
}
