<?php

namespace Tests\Feature;

use Tests\TestCase;

class HeroStaticAssetsTest extends TestCase
{
    public function test_dublin_hero_variants_exist(): void
    {
        foreach ([1, 2, 3, 4] as $scale) {
            $this->assertFileExists(
                public_path("img/static/irsko_dublin_hero-{$scale}x.jpg")
            );
        }
    }
}
