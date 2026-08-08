<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactPageRedesignTest extends TestCase
{
    public function test_contact_page_uses_redesign_sections(): void
    {
        $response = $this->get('/kontakt');

        $response->assertOk();
        $response->assertSee('data-redesign-page="contact"', false);
        $response->assertSee('data-redesign-section="contact-info"', false);
        $response->assertSee('data-redesign-section="contact-form"', false);
        $response->assertSee('<contact-form', false);
        $response->assertSee('text-brand-orange', false);
    }

    public function test_contact_page_shows_contact_details(): void
    {
        $response = $this->get('/kontakt');

        $html = $response->getContent();
        $this->assertStringContainsString(config('contacts.mobile'), $html);
        $this->assertStringContainsString(config('contacts.email'), $html);
        $this->assertStringContainsString(config('contacts.address'), $html);
    }
}
