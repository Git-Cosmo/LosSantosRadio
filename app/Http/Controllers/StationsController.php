<?php

namespace App\Http\Controllers;

use App\Exceptions\AzuraCastException;
use App\Services\AzuraCastService;
use Illuminate\Http\JsonResponse;

class StationsController extends Controller
{
    public function __construct(
        protected AzuraCastService $azuraCast
    ) {}

    /**
     * Display the stations page.
     */
    public function index()
    {
        try {
            $stations = $this->azuraCast->getAllNowPlaying();
        } catch (AzuraCastException $e) {
            return view('stations.index', [
                'error' => 'Unable to fetch station data. Please try again later.',
                'stations' => collect(),
            ]);
        }

        return view('stations.index', [
            'stations' => $stations,
        ]);
    }

    /**
     * Get all public stations as JSON.
     */
    public function list(): JsonResponse
    {
        try {
            $stations = $this->azuraCast->getAllStations();

            return response()->json([
                'success' => true,
                'data' => $stations->map(fn ($station) => $station->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch stations data.',
            ], 503);
        }
    }

    /**
     * Get now playing for all stations as JSON.
     */
    public function nowPlaying(): JsonResponse
    {
        try {
            $nowPlaying = $this->azuraCast->getAllNowPlaying();

            return response()->json([
                'success' => true,
                'data' => $nowPlaying->map(fn ($np) => $np->toArray())->values()->all(),
            ]);
        } catch (AzuraCastException $e) {
            return response()->json([
                'success' => false,
                'error' => 'Unable to fetch now playing data.',
            ], 503);
        }
    }
}
