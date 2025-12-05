<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;

class RobotsController extends Controller
{
    /**
     * Generate robots.txt dynamically with proper sitemap URL.
     */
    public function index(): Response
    {
        $sitemapUrl = url('/sitemap.xml');

        $content = <<<ROBOTS
# Los Santos Radio - robots.txt

User-agent: *
Allow: /

# Disallow admin and private areas
Disallow: /admin/
Disallow: /api/
Disallow: /profile/edit
Disallow: /messages/

# Allow search engines to access public assets
Allow: /images/
Allow: /css/
Allow: /js/

# Sitemap location
Sitemap: {$sitemapUrl}
ROBOTS;

        return response($content)
            ->header('Content-Type', 'text/plain');
    }
}
