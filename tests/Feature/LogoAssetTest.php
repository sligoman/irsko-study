<?php

namespace Tests\Feature;

use Tests\TestCase;

class LogoAssetTest extends TestCase
{
    public function test_navigation_logo_asset_exists(): void
    {
        $this->assertFileExists(public_path('img/logo-irsko-white.svg'));
    }
}
