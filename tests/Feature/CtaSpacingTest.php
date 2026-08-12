<?php

namespace Tests\Feature;

use Tests\TestCase;

class CtaSpacingTest extends TestCase
{
    public function test_shared_cta_has_bottom_spacing_before_footer(): void
    {
        $response = $this->get('/sluzby');

        $response->assertOk();
        $response->assertSee('data-redesign-section="cta-banner" class="home-section pb-16 md:pb-20"', false);
    }
}
