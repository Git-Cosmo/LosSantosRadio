<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Middleware\AdminMiddleware;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
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
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg,pdf,mp3,mp4,webm',
            'collection' => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $collection = $request->input('collection', 'uploads');

        // Create a temporary model to attach media to
        // In a real application, you'd typically attach media to specific models
        // For now, we'll use a generic approach

        $media = Media::create([
            'model_type' => 'App\Models\Setting',
            'model_id' => 1,
            'collection_name' => $collection,
            'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
            'file_name' => $file->getClientOriginalName(),
            'mime_type' => $file->getMimeType(),
            'disk' => 'public',
            'size' => $file->getSize(),
            'manipulations' => [],
            'custom_properties' => [],
            'generated_conversions' => [],
            'responsive_images' => [],
        ]);

        // Move the file to storage
        $path = $file->storeAs(
            "media/{$collection}",
            $media->id.'_'.$file->getClientOriginalName(),
            'public'
        );

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => asset('storage/'.$path),
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
