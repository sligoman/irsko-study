<?php

namespace Tests\Feature;

use Tests\TestCase;

class ServicesPageRedesignTest extends TestCase
{
    public function test_services_page_uses_reusable_redesign_components(): void
    {
        $response = $this->get('/sluzby');

        $response->assertOk();
        $response->assertSee('data-redesign-page="services"', false);
        $response->assertSee('data-component="subpage-hero"', false);
        $response->assertSee('data-component="feature-card"', false);
        $response->assertSee('data-component="summary-list"', false);
        $response->assertSee('data-redesign-section="services-process"', false);
        $response->assertSee('data-redesign-section="services-principles"', false);
        $response->assertSee('text-brand-orange', false);
    }
}
