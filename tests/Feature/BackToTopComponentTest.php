<?php

namespace Tests\Feature;

use Tests\TestCase;

class BackToTopComponentTest extends TestCase
{
    public function test_back_to_top_component_is_centered_and_content_width(): void
    {
        $html = (string) view('components.back-to-top')->render();

        $this->assertStringContainsString('href="#app"', $html);
        $this->assertStringContainsString('Skoč na začátek stránky', $html);
        $this->assertStringContainsString('bg-base-white py-5 text-center', $html);
        $this->assertStringContainsString('inline-flex w-fit', $html);
        $this->assertStringContainsString('<svg', $html);
    }
}
