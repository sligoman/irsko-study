<?php

namespace Tests\Feature;

use Illuminate\Support\Collection;
use Tests\TestCase;

class CourseDetailRedesignTest extends TestCase
{
    private function course(): object
    {
        return (object) [
            'title_cs' => 'Počítačové systémy',
            'title_en' => 'Computer Systems',
            'description_cs' => '<h2>O kurzu</h2><p>Popis kurzu.</p>',
            'description_en' => null,
            'code' => 'CS101',
            'url' => 'atu-cs101',
            'duration_length' => 3,
            'duration_unit' => 'years',
            'level' => (object) ['name' => 'Bachelor'],
            'school' => (object) ['name' => 'Atlantic Technological University', 'school_id' => 'atu', 'link' => 'https://www.atu.ie'],
            'fields' => collect([(object) ['name' => 'Technika']]),
            'link' => 'https://www.atu.ie/cs101',
        ];
    }

    public function test_course_detail_uses_redesign_sections(): void
    {
        $response = $this->view('pages.finder.course', [
            'course' => $this->course(),
            'relatedCourses' => new Collection(),
        ]);

        $response->assertSee('data-redesign-page="course-detail"', false);
        $response->assertSee('data-redesign-section="course-detail-hero"', false);
        $response->assertSee('data-redesign-section="course-detail-content"', false);
        $response->assertSee('Počítačové systémy', false);
        $response->assertSee('text-brand-dark-green', false);
        $response->assertSee('Oficiální web školy', false);
    }

    public function test_course_detail_has_no_legacy_styling(): void
    {
        $blade = file_get_contents(resource_path('views/pages/finder/course.blade.php'));

        $this->assertStringNotContainsString('emerald', $blade);
        $this->assertStringNotContainsString('bg-gray-50', $blade);
    }

    public function test_course_detail_renders_related_courses(): void
    {
        $related = collect([
            (object) [
                'id' => 2,
                'title_cs' => 'Softwarové inženýrství',
                'title_en' => null,
                'description_cs' => 'Popis.',
                'description_en' => null,
                'url' => 'atu-202',
                'school' => (object) ['name' => 'ATU', 'school_id' => 'atu', 'link' => null],
            ],
        ]);

        $response = $this->view('pages.finder.course', [
            'course' => $this->course(),
            'relatedCourses' => $related,
        ]);

        $response->assertSee('data-redesign-section="course-detail-related"', false);
        $response->assertSee('Softwarové inženýrství', false);
    }

    public function test_university_image_component_resolves_existing_files(): void
    {
        $html = (string) view('components.subpage.university-image', [
            'schoolId' => 'atu',
            'name' => 'ATU',
        ])->render();

        $this->assertStringContainsString('uni-atu.jpg', $html);
        $this->assertStringContainsString('<img', $html);
    }


    public function test_course_detail_hero_uses_photo_overlay(): void
    {
        $response = $this->view('pages.finder.course', [
            'course' => $this->course(),
            'relatedCourses' => new Collection(),
        ]);

        $response->assertSee('from-black/10', false);
        $response->assertSee('uni-atu.jpg', false);

        $blade = file_get_contents(resource_path('views/pages/finder/course.blade.php'));
        $this->assertStringNotContainsString('hidden lg:block', $blade);

        $response->assertSee('pt-[104px]', false);
    }
}
