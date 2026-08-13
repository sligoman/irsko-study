<?php

namespace Tests\Feature;

use Tests\TestCase;

class FloatingLeadModalTest extends TestCase
{
    public function test_floating_lead_modal_is_hidden_before_vue_mounts(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('[v-cloak]{display:none!important}', false);
        $response->assertSee('v-show="showContactForm" class="fixed inset-0 z-50 flex items-end justify-center p-3 sm:items-center sm:p-6" style="display: none;"', false);
        $response->assertSee('role="dialog"', false);
        $response->assertSee('aria-modal="true"', false);
        $response->assertSee('<contact-form variant="modal"', false);
        $response->assertDontSee('position="floating"', false);
    }
}
