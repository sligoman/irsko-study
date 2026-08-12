<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class FaqPageRedesignTest extends TestCase
{
    public function test_faq_page_uses_redesign_accordion(): void
    {
        $items = new Collection([
            ['question' => 'Musím umět anglicky?', 'answer' => '<p>Základní předpoklad je středně pokročilá angličtina.</p>'],
            ['question' => 'Jak dlouho trvá přijetí?', 'answer' => '<p>Obvykle několik týdnů po uzávěrce.</p>'],
        ]);

        $response = $this->view('pages.faq', ['items' => $items]);

        $response->assertSee('data-redesign-page="faq"', false);
        $response->assertSee('data-redesign-section="faq-list"', false);
        $response->assertSee('<details', false);
        $response->assertSee('<summary', false);
        $response->assertSee('Musím umět anglicky?', false);
        $response->assertSee('Jak dlouho trvá přijetí?', false);
        $response->assertSee('text-brand-dark-green', false);
    }

    public function test_faq_page_no_longer_uses_vue_accordion(): void
    {
        $blade = file_get_contents(resource_path('views/pages/faq.blade.php'));

        $this->assertStringNotContainsString('faq-accordion', $blade);
    }

    public function test_faq_page_uses_light_hero(): void
    {
        $items = new Collection();

        $response = $this->view('pages.faq', ['items' => $items]);

        $response->assertSee('data-component="subpage-hero-light"', false);
    }
}
