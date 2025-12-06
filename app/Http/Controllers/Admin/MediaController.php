<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
    /**
     * Image quality settings for optimization.
     */
    private const IMAGE_QUALITY_JPEG = 85;

    private const IMAGE_QUALITY_WEBP = 85;

    /**
     * Display media library index page.
     */
    public function index(Request $request): View
    {
        $query = Media::query()->orderBy('created_at', 'desc');

        // Filter by type
        if ($type = $request->input('type')) {
            $query->where('mime_type', 'like', "{$type}%");
        }

        // Search by name
        if ($search = $request->input('search')) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('file_name', 'like', "%{$search}%");
            });
        }

        $media = $query->paginate(20);

        return view('admin.media.index', compact('media'));
    }

    /**
     * Upload new media with image optimization.
     */
    public function upload(Request $request)
    {
        $request->validate([
            // Note: SVG files are excluded from allowed types due to XSS security risks
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,pdf,mp3,mp4,webm',
            'collection' => 'nullable|string|max:100',
        ]);

        $file = $request->file('file');
        $collection = $request->input('collection', 'uploads');

        // Check if it's an image and optimize it
        $isImage = str_starts_with($file->getMimeType(), 'image/');

        // Get or create a Setting model for media attachment
        $setting = Setting::firstOrCreate(
            ['key' => 'media_library'],
            ['value' => 'enabled', 'type' => 'string', 'group' => 'system', 'description' => 'Media library placeholder']
        );

        if ($isImage) {
            // Process image with Intervention Image for web friendliness
            $image = Image::read($file->path());

            // Resize if too large (max 2000px on longest side)
            $image->scaleDown(2000, 2000);

            // Generate optimized file path with UUID for uniqueness
            $uniqueId = Str::uuid()->toString();
            $optimizedPath = storage_path("app/temp/{$uniqueId}_{$file->getClientOriginalName()}");

            // Ensure temp directory exists
            if (! file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // Save with quality optimization
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension === 'jpg' || $extension === 'jpeg') {
                $image->toJpeg(self::IMAGE_QUALITY_JPEG)->save($optimizedPath);
            } elseif ($extension === 'png') {
                $image->toPng()->save($optimizedPath);
            } elseif ($extension === 'webp') {
                $image->toWebp(self::IMAGE_QUALITY_WEBP)->save($optimizedPath);
            } else {
                $image->save($optimizedPath);
            }

            // Add media to the Setting model using Spatie Media Library
            $media = $setting->addMedia($optimizedPath)
                ->usingFileName(Str::uuid()->toString().'_'.$file->getClientOriginalName())
                ->toMediaCollection($collection);

            // Clean up temp file with proper error handling
            if (file_exists($optimizedPath)) {
                if (! unlink($optimizedPath)) {
                    Log::warning("Failed to delete temp file: {$optimizedPath}");
                }
            }
        } else {
            // Add non-image files directly using Spatie Media Library
            $media = $setting->addMedia($file)
                ->usingFileName(Str::uuid()->toString().'_'.$file->getClientOriginalName())
                ->toMediaCollection($collection);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $media->id,
                'path' => $media->getPath(),
                'name' => $media->name,
                'file_name' => $media->file_name,
                'mime_type' => $media->mime_type,
                'size' => $media->size,
                'url' => $media->getUrl(),
            ],
        ], 201);
    }

    /**
     * Delete media.
     */
    public function destroy(Media $media)
    {
        $media->delete();

        return response()->json([
            'success' => true,
            'message' => 'Media deleted successfully.',
        ]);
    }
}
