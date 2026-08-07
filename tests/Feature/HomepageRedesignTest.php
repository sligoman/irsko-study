<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageRedesignTest extends TestCase
{
    public function test_homepage_uses_redesign_section_patterns(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-redesign-section="hero-stats"', false);
        $response->assertSee('data-redesign-section="intro-boxes"', false);
        $response->assertSee('data-redesign-section="programs-overview"', false);
        $response->assertSee('data-redesign-section="steps-timeline"', false);
        $response->assertSee('data-redesign-section="contact-panel"', false);
        $response->assertSee('data-redesign-section="news-grid"', false);
        $response->assertSee('brand-orange', false);
        $response->assertSee('university-slideshow', false);
    }
}
