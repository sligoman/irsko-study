<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\File;
use Sligoman\AiblogApiWeb\Models\AiblogPost;

class SitemapController extends Controller
{
    /**
     * Return the static sitemap index if present. If missing, attempt an on-demand
     * generation (synchronous) and then return the index. The heavy lifting is
     * implemented in `App\Services\SitemapGenerator` and should be scheduled
     * via `php artisan sitemap:generate` in production.
     */
    public function xml(Request $request)
    {
        $sitemapDir = public_path('sitemaps');
        $indexPath = $sitemapDir . DIRECTORY_SEPARATOR . 'sitemap-index.xml';

        if (!File::exists($indexPath)) {
            // Try to generate on-demand; if generation fails, return 503
            try {
                $generator = new \App\Services\SitemapGenerator();
                $generator->generate(true); // gzip by default
            } catch (\Throwable $e) {
                Log::error('SitemapController: generation failed: ' . $e->getMessage());
                return response('Sitemap generation in progress or failed', 503);
            }
        }

        if (!File::exists($indexPath)) {
            return response('Sitemap index not available', 503);
        }

        $xml = File::get($indexPath);
        return response($xml, 200)->header('Content-Type', 'application/xml');
    }

    /**
     * Human-readable sitemap page (HTML) that lists key pages and recent posts.
     */
    public function page(Request $request)
    {
        $posts = collect();
        try {
            $posts = AiblogPost::orderBy('updated_at', 'desc')->limit(100)->where('type_id', 2)->get();
        } catch (\Throwable $e) {
            Log::warning('SitemapController::page - could not load posts: ' . $e->getMessage());
        }

        return view('pages.sitemap', ['posts' => $posts]);
    }
}
