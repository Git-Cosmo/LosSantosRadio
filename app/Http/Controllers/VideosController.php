<?php

namespace App\Http\Controllers;

use App\Models\Video;
use Illuminate\Http\Request;
use Illuminate\View\View;

class VideosController extends Controller
{
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

        // Filter by platform
        if ($request->filled('platform')) {
            $query->platform($request->platform);
        }

        $videos = $query->paginate(20);

        return view('videos.clips', [
            'videos' => $videos,
            'platform' => $request->platform,
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
