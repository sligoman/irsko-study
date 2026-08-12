<?php

namespace Tests\Feature;

use Tests\TestCase;

class WhyIrelandPageRedesignTest extends TestCase
{
    public function test_why_ireland_page_uses_reusable_redesign_components(): void
    {
        $response = $this->get('/proc-irsko');

        $response->assertOk();
        $response->assertSee('data-redesign-page="why-ireland"', false);
        $response->assertSee('data-component="subpage-hero"', false);
        $response->assertSee('data-component="feature-card"', false);
        $response->assertSee('data-component="summary-list"', false);
        $response->assertSee('data-redesign-section="why-ireland-benefits"', false);
        $response->assertSee('data-redesign-section="why-ireland-summary"', false);
        $response->assertSee('text-brand-orange', false);
        $response->assertDontSee('radial-gradient', false);
    }
}
