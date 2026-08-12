<?php

namespace Tests\Feature;

use Tests\TestCase;

class PrivacyPageRedesignTest extends TestCase
{
    public function test_privacy_page_uses_redesign_structure_and_real_contact(): void
    {
        $response = $this->get('/ochrana-soukromi');

        $response->assertOk();
        $response->assertSee('data-redesign-page="privacy"', false);
        $response->assertSee('data-redesign-section="privacy-content"', false);
        $response->assertSee('text-brand-dark-green', false);
        $response->assertSee('text-brand-orange', false);

        $this->assertStringContainsString(
            config('contacts.email'),
            $response->getContent()
        );
    }

    public function test_privacy_page_has_no_placeholder_contact(): void
    {
        $response = $this->get('/ochrana-soukromi');

        $this->assertStringNotContainsString('privacy@irsko.ie', $response->getContent());
        $this->assertStringNotContainsString('upravte na reálný kontakt', $response->getContent());
    }

    public function test_privacy_page_uses_light_hero(): void
    {
        $response = $this->get('/ochrana-soukromi');

        $response->assertOk();
        $response->assertSee('data-component="subpage-hero-light"', false);
    }
}
