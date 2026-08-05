<?php

namespace Tests\Feature;

use App\Http\Controllers\BlogController;
use Illuminate\Support\Facades\File;
use ReflectionMethod;
use Tests\TestCase;

class BlogInlineImageVariantsTest extends TestCase
{
    public function test_blog_content_images_are_rewritten_to_flat_variants(): void
    {
        $blogDir = public_path('img/blog');
        File::ensureDirectoryExists($blogDir);
        $paths = [];

        foreach ([1, 2, 3, 4] as $scale) {
            $paths[] = $blogDir . "/inline-test-{$scale}x.jpg";
        }

        try {
            foreach ($paths as $path) {
                File::put($path, 'test');
            }

            $controller = new BlogController();
            $method = new ReflectionMethod($controller, 'enhanceBlogContentImages');
            $method->setAccessible(true);

            $html = $method->invoke($controller, '<p><img src="/img/blog/inline-test.jpg" alt="Inline"></p>');

            $this->assertStringContainsString('/img/blog/inline-test-3x.jpg', $html);
            $this->assertStringContainsString('/img/blog/inline-test-1x.jpg 640w', $html);
            $this->assertStringContainsString('/img/blog/inline-test-2x.jpg 960w', $html);
            $this->assertStringContainsString('/img/blog/inline-test-4x.jpg 1600w', $html);
            $this->assertStringNotContainsString('/img/blog/large/inline-test.jpg', $html);
        } finally {
            foreach ($paths as $path) {
                File::delete($path);
            }
        }
    }
}
