<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sligoman\AiblogApiWeb\Models\AiblogPost;

class BlogController extends Controller
{
    /**
     * Show paginated list of blog posts from the aiblog package.
     */
    public function index(Request $request)
    {

        $posts = AiblogPost::with('type')->whereHas('type', function ($query) {
            $query->whereIn('name', ['blog','news']);
        })->orderBy('created_at', 'desc')->paginate(10);

        // Transform the underlying collection of the paginator so we keep the
        // LengthAwarePaginator instance (so ->links() still works) but can modify
        // each item for display purposes.
        $posts->setCollection($posts->getCollection()->transform(function ($post) {
            $featuredFilename = null;
            if (! empty($post->featured_image) && is_string($post->featured_image)) {
                // dump( 'Featured image before: ' . $post->featured_image );
                $featuredFilename = $this->stripFeaturedImageFilename($post->featured_image);
                // dump( 'Featured image after: ' . $featuredFilename );
            }
            // store filename (or null) in featured_image for listing view
            $post->featured_image = $featuredFilename;

            return $post;
        }));

        // foreach( $posts as $post ) {
        //     dump($post->featured_image);
        // }

        return view('blog.index', ['posts' => $posts]);
    }

    /**
     * Show a single blog post by slug.
     */
    public function show(Request $request, $slug)
    {

        $post = AiblogPost::where('slug', $slug)->firstOrFail();

        // Adjust image paths inside post content so desktop variants are used when needed
        if (! empty($post->content) && is_string($post->content)) {
            $post->content = $this->addDesktopFolderToBlogImages($post->content);
        }

        return view('blog.show', ['post' => $post]);
    }

    /**
     * Take HTML content and insert a `/desktop/` folder into any image URLs
     * that point to `https://irskostudy.cz/img/blog/` so the desktop-sized
     * variant is used. This is idempotent (won't insert multiple `/desktop/`).
     *
     * @param  string  $content
     * @return string
     */
    protected function addDesktopFolderToBlogImages(string $content): string
    {
        // Match the exact host path and ensure we don't already have /desktop/ after /blog/
        $pattern = '#(https?://(?:www\.)?irskostudy\.cz/img/blog/)(?!desktop/)#i';
        $replacement = '$1desktop/';

        return preg_replace($pattern, $replacement, $content);
    }

    /**
     * If the given featured image path starts with the exact prefix
     * "https://irskostudy.cz/img/blog/", return only the filename portion.
     * Otherwise return null.
     *
     * Examples:
     *  - https://irskostudy.cz/img/blog/denstudentu2.jpeg => denstudentu2.jpeg
     *  - /img/blog/denstudentu2.jpeg => null (not matched)
     *
     * @param  string|null  $path
     * @return string|null
     */
    protected function stripFeaturedImageFilename(?string $path): ?string
    {
        if (empty($path) || ! is_string($path)) {
            return null;
        }

        $prefix = 'https://irskostudy.cz/img/blog/';
        if (str_starts_with($path, $prefix)) {
            return ltrim(substr($path, strlen($prefix)), '/');
        }

        return null;
    }
}
