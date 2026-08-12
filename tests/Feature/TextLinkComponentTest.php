<?php

namespace Tests\Feature;

use Tests\TestCase;

class TextLinkComponentTest extends TestCase
{
    public function test_text_link_matches_irsko_icon_link_treatment(): void
    {
        $html = (string) view('components.subpage.text-link', [
            'href' => '/blog/example',
        ])->with(['slot' => 'Přečíst'])->render();

        $this->assertStringContainsString('Přečíst', $html);
        $this->assertStringContainsString('type-input-label', $html);
        $this->assertStringContainsString('transition-move-figma', $html);
        $this->assertStringContainsString('<svg', $html);
        $this->assertStringContainsString('fill="#9BCC57"', $html);
    }
}
