<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactFormComponentTest extends TestCase
{
    public function test_contact_form_fixed_modal_only_renders_for_floating_variant(): void
    {
        $component = file_get_contents(resource_path('js/components/contact-form.vue'));

        $this->assertStringContainsString('v-show="position === \'floating\' && visible"', $component);
        $this->assertStringNotContainsString('v-show="visible" class="fixed inset-0 z-50 flex items-center justify-center"', $component);
        $this->assertStringContainsString('v-model="form.consent"', $component);
        $this->assertStringContainsString('Souhlasím se zpracováním osobních údajů', $component);
    }
}
