<?php

namespace Tests\Feature;

use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class BlogImageVariantsTest extends TestCase
{
    public function test_blog_show_uses_flat_one_to_four_x_image_variants(): void
    {
        $blogDir = public_path('img/blog');
        File::ensureDirectoryExists($blogDir);
        $paths = [];

        foreach ([1, 2, 3, 4] as $scale) {
            $paths[] = $blogDir . "/test-featured-{$scale}x.jpg";
        }

        try {
            foreach ($paths as $path) {
                File::put($path, 'test');
            }

            $post = (object) [
                'title' => 'Variant Test Article',
                'slug' => 'variant-test-article',
                'excerpt' => 'Testing responsive variants.',
                'content' => '<p>Body</p>',
                'featured_image' => 'test-featured.jpg',
                'created_at' => Carbon::parse('2026-08-04'),
            ];

            $html = (string) view('blog.show', ['post' => $post])->render();

            $this->assertStringContainsString('/img/blog/test-featured-4x.jpg 1600w', $html);
            $this->assertStringNotContainsString('/img/blog/test-featured-1x.jpg 640w', $html);
            $this->assertStringNotContainsString('/img/blog/test-featured-2x.jpg 960w', $html);
            $this->assertStringNotContainsString('/img/blog/test-featured-3x.jpg 1200w', $html);
            $this->assertStringNotContainsString('/img/blog/medium/test-featured.jpg', $html);
            $this->assertStringNotContainsString('/img/blog/large/test-featured.jpg', $html);
        } finally {
            foreach ($paths as $path) {
                File::delete($path);
            }
        }
    }
}
