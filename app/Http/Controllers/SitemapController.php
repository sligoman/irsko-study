<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\URL;
use Sligoman\AiblogApiWeb\Models\AiblogPost;

class SitemapController extends Controller
{
    /**
     * Return sitemap XML including static pages and blog posts (if available).
     */
    public function xml(Request $request)
    {

        $posts = [];

        $posts = AiblogPost::with('type')->whereHas('type', function ($query) {
            $query->whereIn('name', ['blog','news']);
        })->orderBy('updated_at', 'desc')->get();

        $staticUrls = [
            ['loc' => URL::to('/'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'weekly', 'priority' => '1.0'],
            ['loc' => URL::to('/o-nas'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => URL::to('/proc-irsko'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => URL::to('/vysoke-skoly'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.8'],
            ['loc' => URL::to('/sluzby'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.7'],
            ['loc' => URL::to('/faq'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.6'],
            ['loc' => URL::to('/kontakt'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'monthly', 'priority' => '0.5'],
            ['loc' => URL::to('/ochrana-soukromi'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'yearly', 'priority' => '0.3'],
            ['loc' => URL::to('/blog'), 'lastmod' => now()->toAtomString(), 'changefreq' => 'daily', 'priority' => '0.9'],
        ];

        return response()->view('sitemap.xml', ['staticUrls' => $staticUrls, 'posts' => $posts])->header('Content-Type', 'application/xml');
    }

    /**
     * Human-readable sitemap page.
     */
    public function page()
    {

        $posts = AiblogPost::with('type')->whereHas('type', function ($query) {
            $query->whereIn('name', ['blog','news']);
        })->orderBy('created_at', 'desc')->get();

        return view('pages.sitemap', ['posts' => $posts]);
    }
}
