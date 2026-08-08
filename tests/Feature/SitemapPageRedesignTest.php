<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class SitemapPageRedesignTest extends TestCase
{
    public function test_sitemap_page_is_single_clean_redesign(): void
    {
        $posts = new Collection();

        $response = $this->view('pages.sitemap', ['posts' => $posts]);

        $response->assertSee('data-redesign-page="sitemap"', false);
        $response->assertSee('data-redesign-section="sitemap-links"', false);
        $response->assertSee('text-brand-dark-green', false);
        $response->assertSee('Kurzy a programy', false);
        $response->assertSee(route('finder.courses'), false);
    }

    public function test_sitemap_blade_has_no_duplicate_layout_blocks(): void
    {
        $blade = file_get_contents(resource_path('views/pages/sitemap.blade.php'));

        $this->assertStringContainsString("@extends('layouts.app')", $blade);
        $this->assertEquals(1, substr_count($blade, "@extends('layouts.app')"));
        $this->assertEquals(1, substr_count($blade, "@section('content')"));
    }

    public function test_sitemap_lists_blog_posts(): void
    {
        $posts = collect([
            (object) ['slug' => 'jak-studovat', 'title' => 'Jak studovat v Irsku'],
        ]);

        $response = $this->view('pages.sitemap', ['posts' => $posts]);

        $response->assertSee('Jak studovat v Irsku', false);
    }
}
