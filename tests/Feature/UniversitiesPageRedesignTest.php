<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class UniversitiesPageRedesignTest extends TestCase
{
    public function test_universities_page_uses_reusable_redesign_components(): void
    {
        $schools = new Collection([
            (object) [
                'id' => 1,
                'school_id' => 'atu',
                'name' => 'Atlantic Technological University',
                'acronym' => 'ATU',
                'description_cs' => 'Moderní univerzita s prakticky orientovanými programy.',
                'description_en' => null,
                'link' => 'https://www.atu.ie',
                'courses_count' => 12,
                'url' => 'atlantic-technological-university',
            ],
        ]);

        $response = $this->view('pages.universities', ['schools' => $schools]);

        $response->assertSee('data-redesign-page="universities"', false);
        $response->assertSee('data-component="subpage-hero"', false);
        $response->assertSee('data-component="school-card"', false);
        $response->assertSee('data-component="feature-card"', false);
        $response->assertSee('data-component="summary-list"', false);
        $response->assertSee('data-redesign-section="universities-grid"', false);
        $response->assertSee('data-redesign-section="universities-guide"', false);
        $response->assertSee('text-brand-orange', false);
    }
}
