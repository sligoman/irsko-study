<?php

namespace Tests\Feature;

use Tests\TestCase;

class ContactFormComponentTest extends TestCase
{
    public function test_contact_form_uses_shared_inline_and_modal_variants(): void
    {
        $component = file_get_contents(resource_path('js/components/contact-form.vue'));

        $this->assertStringContainsString("variant: { type: String, default: 'inline' }", $component);
        $this->assertStringContainsString("v-if=\"variant === 'inline'\"", $component);
        $this->assertStringContainsString('Vyberte program', $component);
        $this->assertStringContainsString('prefillMessage', $component);
        $this->assertStringNotContainsString("position === 'floating'", $component);
        $this->assertStringContainsString('v-model="form.consent"', $component);
        $this->assertStringContainsString('Souhlasím se zpracováním osobních údajů', $component);
    }
}
