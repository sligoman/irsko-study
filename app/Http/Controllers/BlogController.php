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
            $post->content = $this->addLargeFolderToBlogImages($post->content);
        }

        // Normalize featured_image to a bare filename so views can build the
        // correct local asset paths (avoid concatenating full URLs into asset()).
        if (! empty($post->featured_image) && is_string($post->featured_image)) {
            $post->featured_image = $this->stripFeaturedImageFilename($post->featured_image);
        }

        // Ensure YouTube iframes have the "w-full" class for responsiveness
        if (! empty($post->content) && is_string($post->content)) {
            $post->content = $this->addWFullToYoutubeIframes($post->content);
        }

        return view('blog.show', ['post' => $post]);
    }

    /**
     * Take HTML content and insert a `/large/` folder into any image URLs
     * that point to `https://irskostudy.cz/img/blog/` so the large-sized
     * variant is used. This is idempotent (won't insert multiple `/large/`).
     *
     * @param  string  $content
     * @return string
     */
    protected function addLargeFolderToBlogImages(string $content): string
    {
        // Match either the absolute host path or a leading relative /img/blog/
        // and insert a single `/large/` segment if it isn't already present.
        // This handles both forms like:
        //   https://irskostudy.cz/img/blog/...
        //   /img/blog/...
        // and avoids inserting multiple `/large/` segments.
        $pattern = '#(https?://(?:www\.)?irskostudy\.cz/img/blog/|/img/blog/)(?!large/)#i';
        $replacement = '$1large/';

        return preg_replace($pattern, $replacement, $content);
    }

    /**
     * Find YouTube <iframe> tags in the given HTML and ensure they have the
     * class "w-full". If a class attribute exists, append "w-full" unless it's
     * already present. Non-YouTube iframes are left untouched.
     */
    protected function addWFullToYoutubeIframes(string $content): string
    {
        return preg_replace_callback('#<iframe\b([^>]*)>#i', function ($match) {
            $attrs = $match[1];

            // extract src
            if (! preg_match('/\bsrc\s*=\s*([\'"])(.*?)\1/i', $attrs, $srcMatch)) {
                return $match[0];
            }

            $src = $srcMatch[2];
            if (stripos($src, 'youtube.com') === false && stripos($src, 'youtu.be') === false) {
                return $match[0];
            }

            // if class exists, append w-full if not present
            if (preg_match('/\bclass\s*=\s*([\'"])(.*?)\1/i', $attrs, $classMatch)) {
                $quote = $classMatch[1];
                $classes = $classMatch[2];

                if (preg_match('/\bw-full\b/i', $classes)) {
                    return $match[0];
                }

                $newClasses = $classes . ' w-full';
                $newAttrs = preg_replace(
                    '/\bclass\s*=\s*([\'"])(.*?)\1/i',
                    'class=' . $quote . $newClasses . $quote,
                    $attrs,
                    1
                );

                return '<iframe' . $newAttrs . '>';
            }

            // no class attribute -> add one
            $prefix = ($attrs === '' || $attrs[0] === ' ') ? '' : ' ';
            return '<iframe' . $attrs . $prefix . 'class="w-full">';
        }, $content);
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

        // Use the URL path (if it's a full URL) or the string itself, then
        // return the basename. This covers:
        //  - https://irskostudy.cz/img/blog/den.jpg
        //  - /img/blog/den.jpg
        //  - den.jpg
        $path = trim($path);
        $urlPath = parse_url($path, PHP_URL_PATH) ?: $path;
        $filename = basename($urlPath);

        return $filename !== '' ? $filename : null;
    }
}
