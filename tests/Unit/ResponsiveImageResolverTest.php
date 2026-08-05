<?php

namespace Tests\Unit;

use App\Support\ResponsiveImageResolver;
use PHPUnit\Framework\TestCase;

class ResponsiveImageResolverTest extends TestCase
{
    public function test_image_filename_uses_url_path_without_query_string(): void
    {
        $this->assertSame(
            'sample-image.jpg',
            ResponsiveImageResolver::imageFilename('https://irskostudy.cz/img/blog/sample-image.jpg?v=1#hero')
        );
    }
}
