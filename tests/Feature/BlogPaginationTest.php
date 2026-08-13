<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Tests\TestCase;

class BlogPaginationTest extends TestCase
{
    public function test_guide_pagination_is_czech_and_uses_the_quiet_navigation_style(): void
    {
        $paginator = new LengthAwarePaginator(collect(array_fill(0, 10, (object) ['title' => 'Test článek', 'slug' => 'test', 'excerpt' => 'Úryvek', 'content' => 'Obsah', 'featured_image' => null, 'image' => null, 'categories' => collect()])), 20, 10, 2, ['path' => route('blog')]);
        $html = (string) view('blog.index', ['posts' => $paginator])->render();

        $this->assertStringContainsString('Praktický průvodce - IrskoStudy', $html);
        $this->assertStringContainsString('Předchozí', $html);
        $this->assertStringContainsString('Další', $html);
        $this->assertStringContainsString('Strana 2 z 2', $html);
        $this->assertStringNotContainsString('Previous', $html);
        $this->assertStringNotContainsString('Next', $html);
        $this->assertStringContainsString('transition-move-figma', $html);
        $this->assertStringNotContainsString('bg-brand-light-green', $html);
    }
}
