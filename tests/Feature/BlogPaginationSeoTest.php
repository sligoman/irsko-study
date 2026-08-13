<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Tests\TestCase;

class BlogPaginationSeoTest extends TestCase
{
    public function test_paginated_guide_page_has_self_canonical_and_adjacent_links(): void
    {
        $posts = collect(array_fill(0, 10, (object) ["title" => "Test", "slug" => "test", "excerpt" => "Úryvek", "content" => "Obsah", "featured_image" => null, "image" => null, "categories" => collect()]));
        $paginator = new LengthAwarePaginator($posts, 30, 10, 2, ["path" => route("blog")]);
        $html = (string) view("blog.index", ["posts" => $paginator])->render();

        $this->assertStringContainsString("<link rel=\"canonical\" href=\"" . route("blog") . "?page=2\">", $html);
        $this->assertStringContainsString("<link rel=\"prev\" href=\"" . route("blog") . "?page=1\">", $html);
        $this->assertStringContainsString("<link rel=\"next\" href=\"" . route("blog") . "?page=3\">", $html);
    }
}
