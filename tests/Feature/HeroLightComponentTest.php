<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeroLightComponentTest extends TestCase
{
    public function test_hero_light_has_no_dark_green_surface(): void
    {
        $html = (string) view('components.subpage.hero-light', [
            'eyebrow' => 'Kontakt',
            'title' => 'Napiš nám a domluv si',
            'accent' => 'konzultaci zdarma',
            'text' => 'Ozveme se s dalším krokem.',
            'stats' => [
                ['value' => '30 min', 'label' => 'úvodní rozhovor'],
                ['value' => '0 Kč', 'label' => 'nezávazný začátek'],
            ],
        ])->render();

        $this->assertStringContainsString('data-component="subpage-hero-light"', $html);
        $this->assertStringNotContainsString('bg-brand-dark-green', $html);
        $this->assertStringContainsString('text-brand-dark-green', $html);
        $this->assertStringContainsString('text-brand-orange', $html);
        $this->assertStringContainsString('30 min', $html);
        $this->assertStringContainsString('úvodní rozhovor', $html);
    }
}
