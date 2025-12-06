<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    public function __construct()
    {
        $this->middleware(AdminMiddleware::class);
    }

    /**
     * List all media items.
     */
    public function index(Request $request): JsonResponse
    {
        $perPage = min(100, max(1, (int) $request->input('per_page', 20)));
        $type = $request->input('type');

        $query = Media::query()->orderBy('created_at', 'desc');

        if ($type) {
            $query->where('mime_type', 'like', "{$type}%");
        }

        $media = $query->paginate($perPage);

        return response()->json([
            'success' => true,
            'data' => $media->items(),
            'meta' => [
                'current_page' => $media->currentPage(),
                'last_page' => $media->lastPage(),
                'per_page' => $media->perPage(),
                'total' => $media->total(),
            ],
        ]);
    }

    /**
     * Upload new media.
     */
    public function upload(Request $request): JsonResponse
    {
        $request->validate([
            // Note: SVG files are excluded from allowed types due to XSS security risks
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,mp3,mp4,webm',
            'collection' => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $collection = $request->input('collection', 'uploads');

        // Get or create a Setting model for media attachment
        // This properly uses Spatie Media Library's polymorphic relationship
        $setting = Setting::firstOrCreate(
            ['key' => 'media_library'],
            ['value' => 'enabled', 'type' => 'string', 'group' => 'system', 'description' => 'Media library placeholder']
        );

        // Add media using Spatie Media Library's proper API
        $media = $setting->addMedia($file)
            ->usingFileName(Str::uuid()->toString().'_'.$file->getClientOriginalName())
            ->toMediaCollection($collection);

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => $media->getUrl(),
            ],
        ], 201);
    }

    /**
     * Delete a media item.
     */
    public function destroy(Media $media): JsonResponse
    {
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }
}
