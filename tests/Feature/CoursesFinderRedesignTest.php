<?php

namespace Tests\Feature;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Tests\TestCase;

class CoursesFinderRedesignTest extends TestCase
{
    public function test_courses_page_uses_redesign_sections(): void
    {
        $courses = collect([
            (object) ['id' => 1, 'title_cs' => 'Počítačové systémy', 'title_en' => 'Computer Systems', 'url' => 'atu-101', 'school' => (object) ['name' => 'ATU'], 'description_cs' => 'Popis.', 'description_en' => null],
        ]);

        $paginator = new LengthAwarePaginator($courses, $courses->count(), 12, 1, [
            'path' => route('finder.courses'),
        ]);

        $initialData = [
            'schools' => new Collection([(object) ['id' => 1, 'school_id' => 'atu', 'name' => 'Atlantic Technological University']]),
            'fields' => new Collection([(object) ['id' => 1, 'description' => 'Technika']]),
            'levels' => new Collection([(object) ['id' => 1, 'name' => 'Bachelor']]),
            'results' => $paginator,
            'filters' => ['q' => null, 'school' => null, 'field' => null, 'level' => null, 'page' => 1],
        ];

        $response = $this->view('pages.finder.courses', ['initialData' => $initialData]);

        $response->assertSee('data-redesign-page="courses-finder"', false);
        $response->assertSee('data-redesign-section="course-finder"', false);
        $response->assertSee('data-component="subpage-hero-photo"', false);
        $response->assertSee('irsko_dublin_hero', false);
        $response->assertSee('<course-finder', false);
    }

    public function test_courses_page_has_no_duplicate_app_mount(): void
    {
        $blade = file_get_contents(resource_path('views/pages/finder/courses.blade.php'));

        $this->assertStringNotContainsString('id="app"', $blade);
    }

    public function test_course_finder_vue_uses_brand_tokens(): void
    {
        $vue = file_get_contents(resource_path('js/components/course-finder.vue'));

        $this->assertStringContainsString('brand-dark-green', $vue);
        $this->assertStringContainsString('brand-light-green', $vue);
        $this->assertStringContainsString('brand-orange', $vue);
        $this->assertStringNotContainsString('emerald', $vue);
    }
}
