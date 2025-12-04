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
     * Display the songs page.
     */
    public function index(Request $request)
    {
        $nowPlaying = null;
        $history = collect();
        $error = null;
        $search = $request->query('search', '');
        $searchResults = collect();

        try {
            $nowPlaying = $this->azuraCast->getNowPlaying();
            $history = $this->azuraCast->getHistory(20);

            if ($search) {
                $searchResults = $this->azuraCast->searchLibrary($search, 25);
            }
        } catch (AzuraCastException $e) {
            $error = 'Unable to fetch song data. Please try again later.';
        }

        return view('songs.index', [
            'nowPlaying' => $nowPlaying,
            'history' => $history,
            'error' => $error,
            'search' => $search,
            'searchResults' => $searchResults,
        ]);
    }
}
