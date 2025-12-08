<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideosController extends Controller
{
    /**
     * Display all videos.
     */
    public function index(Request $request): View
    {
        $videos = Video::active()
            ->orderBy('posted_at', 'desc')
            ->paginate(20);

        return view('videos.index', [
            'videos' => $videos,
        ]);
    }

    /**
     * Display YLYL (You Laugh You Lose) videos.
     */
    public function ylyl(Request $request): View
    {
        $videos = Video::active()
            ->ylyl()
            ->orderBy('posted_at', 'desc')
            ->paginate(20);

        return view('videos.ylyl', [
            'videos' => $videos,
        ]);
    }

    /**
     * Display streamer clips.
     */
    public function clips(Request $request): View
    {
        $query = Video::active()
            ->clips()
            ->orderBy('posted_at', 'desc');

        // Filter by platform (validated against whitelist to prevent SQL injection)
        $allowedPlatforms = ['twitch', 'youtube', 'kick'];
        $platform = $request->input('platform');
        if ($platform && in_array($platform, $allowedPlatforms, true)) {
            $query->platform($platform);
        } else {
            $platform = null;
        }

        $videos = $query->paginate(20);

        return view('videos.clips', [
            'videos' => $videos,
            'platform' => $platform,
        ]);
    }

    /**
     * Show a single video.
     */
    public function show(Video $video): View
    {
        $video->incrementViews();

        $relatedVideos = Video::active()
            ->where('id', '!=', $video->id)
            ->where('category', $video->category)
            ->orderBy('posted_at', 'desc')
            ->limit(6)
            ->get();

        return view('videos.show', [
            'video' => $video,
            'relatedVideos' => $relatedVideos,
        ]);
    }
}
