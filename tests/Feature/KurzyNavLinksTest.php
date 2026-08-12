<?php

namespace Tests\Feature;

use Tests\TestCase;

class KurzyNavLinksTest extends TestCase
{
    public function test_navbar_contains_kurzy_link(): void
    {
        $blade = file_get_contents(resource_path('views/components/navbar.blade.php'));

        $this->assertStringContainsString("'label' => 'Kurzy'", $blade);
        $this->assertStringContainsString("route('finder.courses')", $blade);
        $this->assertStringContainsString("'kurzy'", $blade);
    }

    public function test_footer_contains_kurzy_link(): void
    {
        $blade = file_get_contents(resource_path('views/components/footer.blade.php'));

        $this->assertStringContainsString('>Kurzy</a>', $blade);
        $this->assertStringContainsString("route('finder.courses')", $blade);
    }

    public function test_homepage_renders_kurzy_link(): void
    {
        $response = $this->get('/');

        $response->assertOk();
        $response->assertSee('Kurzy', false);
        $response->assertSee(route('finder.courses'), false);
    }
}
