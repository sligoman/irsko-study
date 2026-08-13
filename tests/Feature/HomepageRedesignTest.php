<?php

namespace Tests\Feature;

use Tests\TestCase;

class HomepageRedesignTest extends TestCase
{
    public function test_homepage_uses_reference_style_consultation_panel(): void
    {
        $response = $this->get("/");

        $response->assertOk();
        $response->assertSee("redesign-footer-pattern", false);
        $response->assertSee("img/static/irsko_formular_brozura_detail-1x.webp", false);
        $response->assertSee("<contact-form variant=\"inline\"></contact-form>", false);
    }

    public function test_homepage_uses_redesign_section_patterns(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('data-redesign-section="hero-stats"', false);
        $response->assertSee('data-redesign-section="intro-boxes"', false);
        $response->assertSee('data-redesign-section="programs-overview"', false);
        $response->assertSee('data-redesign-section="steps-timeline"', false);
        $response->assertSee('data-redesign-section="contact-panel"', false);
        $response->assertSee('data-redesign-section="student-video"', false);
        $response->assertSee('data-redesign-section="news-grid"', false);
        $response->assertSee('img/video.mp4', false);
        $response->assertSee('brand-orange', false);
        $response->assertSee('university-slideshow', false);
        $response->assertDontSee('radial-gradient', false);
        $response->assertSee('id="redesign-nav"', false);
        $response->assertSee('sticky top-0 z-50 bg-brand-dark-green', false);
    }

    public function test_hero_university_slideshow_assets_are_available(): void
    {
        $this->assertFileExists(public_path('img/universities/universities.json'));
        $this->assertFileExists(public_path('img/universities/uni-atu.jpg'));
        $this->assertFileExists(public_path('img/universities/uni-dcu.jpg'));
        $this->assertFileExists(public_path('img/universities/uni-trinity.png'));

        $component = file_get_contents(resource_path('js/components/university-slideshow.vue'));

        $this->assertStringContainsString('/img/universities/', $component);
        $this->assertStringNotContainsString('/img/blog/medium/', $component);
    }

}
