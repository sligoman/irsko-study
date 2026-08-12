<?php

namespace Tests\Feature;

use Tests\TestCase;

class AboutPageRedesignTest extends TestCase
{
    public function test_about_page_uses_redesign_sections_and_team_assets(): void
    {
        $response = $this->get('/o-nas');

        $response->assertOk();
        $response->assertSee('data-redesign-page="about"', false);
        $response->assertSee('data-component="subpage-hero"', false);
        $response->assertDontSee('radial-gradient', false);
        $response->assertSee('data-redesign-section="about-values"', false);
        $response->assertSee('data-redesign-section="about-team"', false);
        $response->assertSee('data-redesign-section="about-contact"', false);
        $response->assertSee('text-brand-orange', false);
        $response->assertSee('img/team/david_fiala.jpg', false);
        $response->assertSee('img/team/michal_chupik.jpeg', false);

        $this->assertFileExists(public_path('img/team/david_fiala.jpg'));
        $this->assertFileExists(public_path('img/team/michal_chupik.jpeg'));
    }
}
