<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageTextLinkTest extends TestCase
{
    public function test_homepage_uses_the_shared_text_link_component(): void
    {
        $reasons = file_get_contents(resource_path('views/components/reasons.blade.php'));
        $blogPreview = file_get_contents(resource_path('views/components/blog-preview.blade.php'));

        $this->assertStringContainsString("<x-text-link href=\"{{ route('why') }}\">", $reasons);
        $this->assertStringContainsString("class=\"mt-6\">Číst článek</x-text-link>", $blogPreview);
        $this->assertSame(2, substr_count($blogPreview, "class=\"mt-6\">Číst článek</x-text-link>"));
        $this->assertStringContainsString("<x-text-link href=\"{{ route('blog') }}\">", $blogPreview);
    }
}
