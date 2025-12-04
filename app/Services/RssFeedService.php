<?php

namespace App\Services;

use App\Models\News;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class RssFeedService
{
    /**
     * Import news from an RSS feed URL.
     *
     * @return array{imported: int, errors: array<string>}
     */
    public function importFromUrl(string $feedUrl, ?int $authorId = null): array
    {
        $imported = 0;
        $errors = [];

        try {
            $response = Http::timeout(30)->get($feedUrl);

            if (! $response->successful()) {
                return [
                    'imported' => 0,
                    'errors' => ['Failed to fetch RSS feed: HTTP '.$response->status()],
                ];
            }

            $xml = simplexml_load_string($response->body());
            if ($xml === false) {
                return [
                    'imported' => 0,
                    'errors' => ['Invalid XML format in RSS feed'],
                ];
            }

            // Handle both RSS 2.0 and Atom feeds
            $items = $xml->channel->item ?? $xml->entry ?? [];

            foreach ($items as $item) {
                try {
                    $title = (string) ($item->title ?? '');
                    $link = (string) ($item->link ?? $item->link['href'] ?? '');
                    $description = (string) ($item->description ?? $item->content ?? $item->summary ?? '');
                    $pubDate = (string) ($item->pubDate ?? $item->published ?? $item->updated ?? '');

                    if (empty($title)) {
                        continue;
                    }

                    // Skip if we already have this article (by source URL)
                    if ($link && News::where('source_url', $link)->exists()) {
                        continue;
                    }

                    // Extract image from description or media namespace
                    $image = $this->extractImage($item, $description);

                    News::create([
                        'title' => Str::limit($title, 250),
                        'slug' => Str::slug($title),
                        'content' => $this->sanitizeContent($description),
                        'excerpt' => Str::limit(strip_tags($description), 150),
                        'source' => 'rss',
                        'source_url' => $link,
                        'image' => $image,
                        'author_id' => $authorId,
                        'is_published' => true,
                        'published_at' => $this->parseDate($pubDate),
                    ]);

                    $imported++;
                } catch (\Exception $e) {
                    $errors[] = 'Failed to import item: '.$e->getMessage();
                    Log::warning('RSS import item failed', [
                        'url' => $feedUrl,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        } catch (\Exception $e) {
            Log::error('RSS import failed', [
                'url' => $feedUrl,
                'error' => $e->getMessage(),
            ]);

            return [
                'imported' => 0,
                'errors' => ['Failed to import RSS feed: '.$e->getMessage()],
            ];
        }

        return [
            'imported' => $imported,
            'errors' => $errors,
        ];
    }

    /**
     * Extract image URL from RSS item.
     */
    protected function extractImage(\SimpleXMLElement $item, string $description): ?string
    {
        // Check for media:content namespace
        $namespaces = $item->getNamespaces(true);
        if (isset($namespaces['media'])) {
            $media = $item->children($namespaces['media']);
            if (isset($media->content)) {
                $attrs = $media->content->attributes();
                if (isset($attrs['url'])) {
                    return (string) $attrs['url'];
                }
            }
            if (isset($media->thumbnail)) {
                $attrs = $media->thumbnail->attributes();
                if (isset($attrs['url'])) {
                    return (string) $attrs['url'];
                }
            }
        }

        // Check for enclosure
        if (isset($item->enclosure)) {
            $attrs = $item->enclosure->attributes();
            $type = (string) ($attrs['type'] ?? '');
            if (str_starts_with($type, 'image/') && isset($attrs['url'])) {
                return (string) $attrs['url'];
            }
        }

        // Try to extract image from description
        if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/i', $description, $matches)) {
            return $matches[1];
        }

        return null;
    }

    /**
     * Sanitize HTML content.
     */
    protected function sanitizeContent(string $content): string
    {
        // Remove potentially dangerous tags
        $content = preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $content);
        $content = preg_replace('/<style\b[^>]*>(.*?)<\/style>/is', '', $content);
        $content = preg_replace('/<iframe\b[^>]*>(.*?)<\/iframe>/is', '', $content);

        // Allow safe HTML tags
        return strip_tags($content, '<p><br><a><strong><em><ul><ol><li><h1><h2><h3><h4><h5><h6><blockquote><img>');
    }

    /**
     * Parse date string to Carbon instance.
     */
    protected function parseDate(string $dateString): \Carbon\Carbon
    {
        if (empty($dateString)) {
            return now();
        }

        try {
            return \Carbon\Carbon::parse($dateString);
        } catch (\Exception) {
            return now();
        }
    }
}
