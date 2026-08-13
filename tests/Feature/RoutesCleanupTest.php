<?php

namespace Tests\Feature;

use Tests\TestCase;

class RoutesCleanupTest extends TestCase
{
    public function test_welcome_route_is_removed(): void
    {
        $this->get('/welcome')->assertNotFound();
    }

    public function test_root_serves_redesign_home(): void
    {
        $this->get('/')->assertOk();
        $this->get('/')->assertSee('data-redesign-section="hero-stats"', false);
    }

    public function test_web_routes_have_single_root_definition(): void
    {
        $routes = file_get_contents(base_path('routes/web.php'));

        $this->assertStringContainsString("Route::view('/', 'pages.home')->name('home');", $routes);
        $this->assertStringNotContainsString("return view('welcome');", $routes);
        $this->assertStringNotContainsString("'/welcome'", $routes);
    }
    public function test_guide_uses_a_czech_path_and_redirects_legacy_blog_urls(): void
    {
        $this->assertSame(url("/prakticky-pruvodce"), route("blog"));
        $this->assertSame(url("/prakticky-pruvodce/example"), route("blog.show", "example"));
        $this->get("/blog")->assertRedirect("/prakticky-pruvodce");
        $this->get("/blog/example")->assertRedirect("/prakticky-pruvodce/example");
    }
}
