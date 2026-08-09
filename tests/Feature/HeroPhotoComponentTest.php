<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeroPhotoComponentTest extends TestCase
{
    public function test_hero_photo_builds_responsive_srcset(): void
    {
        $html = (string) view('components.subpage.hero-photo', [
            'eyebrow' => 'Kurzy a programy',
            'title' => 'Najdi kurz',
            'accent' => 'sedne',
            'text' => 'Procházej programy.',
            'stats' => [],
            'photo' => [
                'src' => 'img/static/irsko_dublin_hero.jpg',
                'sizes' => '(max-width: 768px) 100vw, 1280px',
                'alt' => 'Dublin, Irsko',
            ],
        ])->render();

        $this->assertStringContainsString('data-component="subpage-hero-photo"', $html);
        $this->assertStringContainsString('from-black/10', $html);
        $this->assertStringContainsString('irsko_dublin_hero-1x.jpg', $html);
        $this->assertStringContainsString('irsko_dublin_hero-4x.jpg', $html);
        $this->assertStringContainsString('640w', $html);
        $this->assertStringContainsString('Najdi kurz', $html);
        $this->assertStringContainsString('pt-[104px]', $html);
    }
}
