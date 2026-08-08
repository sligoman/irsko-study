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
        $response->assertSee('v-show="showContactForm" class="fixed inset-0 z-50 flex items-center justify-center p-4 sm:p-6" style="display: none;"', false);
    }
}
