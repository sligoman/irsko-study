<?php

namespace Tests\Feature;

use Tests\TestCase;

class FaviconTest extends TestCase
{
    public function test_layout_uses_irsko_favicon_assets(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('img/svg/favicon.svg', false);
        $response->assertSee('img/svg/favicon.ico', false);
        $response->assertSee('img/svg/favicon-32x32.png', false);
        $response->assertSee('img/svg/favicon-16x16.png', false);
        $response->assertSee('img/svg/apple-touch-icon.png', false);
        $response->assertSee('img/svg/android-chrome-192x192.png', false);
        $response->assertSee('img/svg/android-chrome-512x512.png', false);
        $response->assertSee('img/site.webmanifest', false);

        foreach ([
            'public/img/svg/favicon.svg',
            'public/img/svg/favicon.ico',
            'public/img/svg/favicon-16x16.png',
            'public/img/svg/favicon-32x32.png',
            'public/img/svg/apple-touch-icon.png',
            'public/img/svg/android-chrome-192x192.png',
            'public/img/svg/android-chrome-512x512.png',
            'public/img/site.webmanifest',
        ] as $asset) {
            $this->assertFileExists(base_path($asset));
        }
    }

    public function test_favicon_uses_brand_orange_for_the_study_accent(): void
    {
        $favicon = strtoupper((string) file_get_contents(base_path('public/img/svg/favicon.svg')));

        $this->assertStringContainsString('#FF782E', $favicon);
        $this->assertStringNotContainsString('#F58320', $favicon);
    }
}
