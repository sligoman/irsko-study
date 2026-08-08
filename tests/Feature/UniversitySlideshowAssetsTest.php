<?php

namespace Tests\Feature;

use Tests\TestCase;

class UniversitySlideshowAssetsTest extends TestCase
{
    public function test_every_json_entry_has_an_asset_file(): void
    {
        $json = json_decode(
            file_get_contents(public_path('img/universities/universities.json')),
            true
        );

        $this->assertNotEmpty($json);

        foreach ($json as $slide) {
            $this->assertArrayHasKey('file', $slide);
            $this->assertFileExists(
                public_path('img/universities/' . $slide['file'])
            );
        }
    }

    public function test_universities_json_route_no_longer_reads_storage(): void
    {
        $routes = file_get_contents(base_path('routes/web.php'));

        $this->assertStringNotContainsString(
            "storage_path('app/universities/universities.json')",
            $routes
        );
    }
}
