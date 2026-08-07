<?php

namespace Tests\Feature;

use Tests\TestCase;

class NavbarLogoTest extends TestCase
{
    public function test_navbar_and_footer_use_white_irsko_logo_with_orange_study_suffix(): void
    {
        $response = $this->get('/');

        $response->assertOk();

        $html = $response->getContent();

        $this->assertGreaterThanOrEqual(2, substr_count($html, 'img/logo-irsko-white.svg'));
        $this->assertGreaterThanOrEqual(2, substr_count($html, 'text-brand-orange'));
        $this->assertGreaterThanOrEqual(2, substr_count($html, '>Study<'));
    }
}
