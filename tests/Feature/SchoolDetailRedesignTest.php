<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class SchoolDetailRedesignTest extends TestCase
{
    private function school(): object
    {
        return (object) [
            'name' => 'Atlantic Technological University',
            'acronym' => 'ATU',
            'school_id' => 'atu',
            'description_cs' => '<h2>O škole</h2><p>Moderní univerzita.</p>',
            'description_en' => null,
            'link' => 'https://www.atu.ie',
            'courses' => new Collection([
                (object) [
                    'id' => 1,
                    'title_cs' => 'Počítačové systémy',
                    'title_en' => null,
                    'description_cs' => 'Popis kurzu.',
                    'description_en' => null,
                    'code' => 'CS101',
                    'url' => 'atu-cs101',
                    'level' => (object) ['name' => 'Bachelor'],
                    'school' => (object) ['school_id' => 'atu', 'name' => 'ATU'],
                ],
            ]),
            'locations' => new Collection([
                (object) ['name' => 'Sligo', 'embed' => '<iframe src="https://maps.google.com/embed"></iframe>', 'map' => 'https://maps.google.com'],
            ]),
        ];
    }

    public function test_school_detail_uses_redesign_sections(): void
    {
        $response = $this->view('pages.finder.school', ['school' => $this->school()]);

        $response->assertSee('data-redesign-page="school-detail"', false);
        $response->assertSee('data-redesign-section="school-detail-hero"', false);
        $response->assertSee('data-redesign-section="school-detail-content"', false);
        $response->assertSee('data-redesign-section="school-detail-courses"', false);
        $response->assertSee('data-redesign-section="school-detail-campuses"', false);
        $response->assertSee('Atlantic Technological University', false);
        $response->assertSee('text-brand-dark-green', false);
    }

    public function test_school_detail_has_no_legacy_styling(): void
    {
        $blade = file_get_contents(resource_path('views/pages/finder/school.blade.php'));

        $this->assertStringNotContainsString('emerald', $blade);
        $this->assertStringNotContainsString('bg-gray-50', $blade);
    }

    public function test_school_detail_renders_courses_and_campuses(): void
    {
        $response = $this->view('pages.finder.school', ['school' => $this->school()]);

        $response->assertSee('Počítačové systémy', false);
        $response->assertSee('Sligo', false);
        $response->assertSee('iframe', false);
    }


    public function test_school_detail_hero_uses_photo_overlay(): void
    {
        $response = $this->view('pages.finder.school', ['school' => $this->school()]);

        $response->assertSee('from-black/10', false);
        $response->assertSee('uni-atu.jpg', false);

        $blade = file_get_contents(resource_path('views/pages/finder/school.blade.php'));
        $this->assertStringNotContainsString('hidden lg:block', $blade);
    }
}
