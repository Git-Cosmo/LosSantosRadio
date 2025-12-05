<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Intervention\Image\Laravel\Facades\Image;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class MediaController extends Controller
{
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
            'file' => 'required|file|max:10240|mimes:jpg,jpeg,png,gif,webp,svg,pdf,mp3,mp4,webm',
            'collection' => 'nullable|string|max:100',
            'model_type' => 'nullable|string|max:255',
            'model_id' => 'nullable|integer',
        ]);

        $file = $request->file('file');
        $collection = $request->input('collection', 'uploads');

        // Check if it's an image and optimize it
        $isImage = str_starts_with($file->getMimeType(), 'image/') && ! str_contains($file->getMimeType(), 'svg');

        if ($isImage) {
            // Process image with Intervention Image for web friendliness
            $image = Image::read($file->path());

            // Resize if too large (max 2000px on longest side)
            $image->scaleDown(2000, 2000);

            // Generate optimized file path
            $optimizedPath = storage_path('app/temp/'.uniqid().'_'.$file->getClientOriginalName());

            // Ensure temp directory exists
            if (! file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0755, true);
            }

            // Save with quality optimization
            $extension = strtolower($file->getClientOriginalExtension());
            if ($extension === 'jpg' || $extension === 'jpeg') {
                $image->toJpeg(85)->save($optimizedPath);
            } elseif ($extension === 'png') {
                $image->toPng()->save($optimizedPath);
            } elseif ($extension === 'webp') {
                $image->toWebp(85)->save($optimizedPath);
            } else {
                $image->save($optimizedPath);
            }

            // Get file size after optimization
            $fileSize = filesize($optimizedPath);

            // Store the optimized image
            $storedPath = $this->storeFile($optimizedPath, $file->getClientOriginalName(), $collection);

            // Clean up temp file
            @unlink($optimizedPath);
        } else {
            // Store non-image files directly
            $storedPath = $file->storeAs(
                "media/{$collection}",
                time().'_'.$file->getClientOriginalName(),
                'public'
            );
            $fileSize = $file->getSize();
        }

        return response()->json([
            'success' => true,
            'data' => [
                'path' => $storedPath,
                'name' => pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'file_name' => $file->getClientOriginalName(),
                'mime_type' => $file->getMimeType(),
                'size' => $fileSize,
                'url' => asset('storage/'.$storedPath),
            ],
        ], 201);
    }

    /**
     * Store a file and return the path.
     */
    protected function storeFile(string $sourcePath, string $originalName, string $collection): string
    {
        $storagePath = "media/{$collection}";
        $fileName = time().'_'.$originalName;
        $fullPath = storage_path("app/public/{$storagePath}");

        // Ensure directory exists
        if (! file_exists($fullPath)) {
            mkdir($fullPath, 0755, true);
        }

        // Copy file to storage
        copy($sourcePath, "{$fullPath}/{$fileName}");

        return "{$storagePath}/{$fileName}";
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
