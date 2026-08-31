<?php

namespace Tests\Feature;

use Tests\TestCase;

class NotFoundPageRedesignTest extends TestCase
{
    public function test_not_found_page_uses_the_redesigned_layout(): void
    {
        $response = $this->get('/stranka-ktera-neexistuje');

        $response->assertNotFound();
        $response->assertSee('Chyba 404');
        $response->assertSee('data-redesign-page="404"', false);
        $response->assertSee('redesign-footer-pattern', false);
        $response->assertSee('Zpět na úvod');
        $response->assertSee('Vysoké školy');
        $response->assertSee('content="noindex"', false);
    }
}
