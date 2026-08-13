<?php

namespace Tests\Feature;

use Tests\TestCase;

class RedesignSvgAssetsTest extends TestCase
{
    public function test_redesign_svg_assets_exist(): void
    {
        foreach (['book-open-text.svg', 'lightbulb-thin.svg', 'pattern-cta.svg', 'suitcase-rolling.svg'] as $asset) {
            $this->assertFileExists(public_path('img/svg/' . $asset));
        }
    }
}
