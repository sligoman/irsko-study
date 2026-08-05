<?php

namespace App\Http\Controllers;

use App\Support\ResponsiveImageResolver;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Sligoman\AiblogApiWeb\Models\AiblogPost;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        $posts = AiblogPost::with('type')->whereHas('type', function ($query) {
            $query->whereIn('name', ['blog', 'news']);
        })->orderBy('created_at', 'desc')->paginate(10);

        $posts->setCollection($posts->getCollection()->transform(function ($post) {
            $this->normalizePostImage($post);

            return $post;
        }));

        return view('blog.index', ['posts' => $posts]);
    }

    public function show(Request $request, $slug)
    {
        $post = AiblogPost::where('slug', $slug)->firstOrFail();

        $this->normalizePostImage($post);

        if (! empty($post->content) && is_string($post->content)) {
            $post->content = $this->enhanceBlogContentImages($post->content);
            $post->content = $this->addWFullToYoutubeIframes($post->content);
        }

        return view('blog.show', ['post' => $post]);
    }

    protected function enhanceBlogContentImages(string $content): string
    {
        return preg_replace_callback('#<img\b([^>]*?)\bsrc\s*=\s*([\'\"])(.*?)\2([^>]*)>#i', function ($match) {
            $src = $match[3];
            $path = parse_url($src, PHP_URL_PATH) ?: $src;

            if (! preg_match('#(?:^|/)img/blog/#i', $path)) {
                return $match[0];
            }

            $filename = ResponsiveImageResolver::imageFilename($src);
            if (empty($filename) || ! ResponsiveImageResolver::hasAnyVariant($filename, 'blog')) {
                return $match[0];
            }

            $image1x = ResponsiveImageResolver::urlForScale($filename, 'blog', 1);
            $image2x = ResponsiveImageResolver::urlForScale($filename, 'blog', 2);
            $image3x = ResponsiveImageResolver::urlForScale($filename, 'blog', 3);
            $image4x = ResponsiveImageResolver::urlForScale($filename, 'blog', 4);
            $imageSrc = $image3x ?? $image2x ?? $image1x ?? $image4x;

            if (empty($imageSrc)) {
                return $match[0];
            }

            $srcset = collect([
                $image1x ? "{$image1x} 640w" : null,
                $image2x ? "{$image2x} 960w" : null,
                $image3x ? "{$image3x} 1200w" : null,
                $image4x ? "{$image4x} 1600w" : null,
            ])->filter()->implode(', ');

            $attributes = trim($match[1] . ' ' . $match[4]);
            $attributes = preg_replace('/\s*srcset\s*=\s*([\'\"])(.*?)\1/i', '', $attributes);
            $attributes = preg_replace('/\s*sizes\s*=\s*([\'\"])(.*?)\1/i', '', $attributes);
            $attributes = trim($attributes);

            return '<img ' . ($attributes !== '' ? $attributes . ' ' : '')
                . 'src="' . e($imageSrc) . '" srcset="' . e($srcset) . '" sizes="(max-width: 768px) 100vw, 768px">';
        }, $content);
    }

    protected function addWFullToYoutubeIframes(string $content): string
    {
        return preg_replace_callback('#<iframe\b([^>]*)>#i', function ($match) {
            $attrs = $match[1];

            if (! preg_match('/\bsrc\s*=\s*([\'\"])(.*?)\1/i', $attrs, $srcMatch)) {
                return $match[0];
            }

            $src = $srcMatch[2];
            if (stripos($src, 'youtube.com') === false && stripos($src, 'youtu.be') === false) {
                return $match[0];
            }

            if (preg_match('/\bclass\s*=\s*([\'\"])(.*?)\1/i', $attrs, $classMatch)) {
                $quote = $classMatch[1];
                $classes = $classMatch[2];

                if (preg_match('/\bw-full\b/i', $classes)) {
                    return $match[0];
                }

                $newAttrs = preg_replace(
                    '/\bclass\s*=\s*([\'\"])(.*?)\1/i',
                    'class=' . $quote . $classes . ' w-full' . $quote,
                    $attrs,
                    1
                );

                return '<iframe' . $newAttrs . '>';
            }

            $prefix = ($attrs === '' || $attrs[0] === ' ') ? '' : ' ';

            return '<iframe' . $attrs . $prefix . 'class="w-full">';
        }, $content);
    }

    private function normalizePostImage(object $post): void
    {
        $resolvedImage = ResponsiveImageResolver::imageFilename($post);
        if (empty($resolvedImage) || ! ResponsiveImageResolver::hasAnyVariant($resolvedImage, 'blog')) {
            $post->featured_image = null;
            $post->image = null;
            return;
        }

        $post->featured_image = $resolvedImage;
        $post->image = $resolvedImage;
    }
}
