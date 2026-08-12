<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Route;
use Tests\TestCase;

class NavbarPhotoHeroTest extends TestCase
{
    public function test_navbar_is_absolute_on_courses_page(): void
    {
        Route::get('/_navtest', fn () => view('components.navbar'))->name('finder.courses');

        $response = $this->get('/_navtest');

        $response->assertSee('absolute inset-x-0 top-0 z-50', false);
        $response->assertDontSee('sticky top-0 z-50', false);
    }

    public function test_navbar_is_absolute_on_course_detail_page(): void
    {
        Route::get('/_navtest', fn () => view('components.navbar'))->name('finder.course.show');

        $response = $this->get('/_navtest');

        $response->assertSee('absolute inset-x-0 top-0 z-50', false);
        $response->assertDontSee('sticky top-0 z-50', false);
    }

    public function test_navbar_is_absolute_on_school_detail_page(): void
    {
        Route::get('/_navtest', fn () => view('components.navbar'))->name('finder.school.show');

        $response = $this->get('/_navtest');

        $response->assertSee('absolute inset-x-0 top-0 z-50', false);
        $response->assertDontSee('sticky top-0 z-50', false);
    }

    public function test_navbar_stays_sticky_on_other_pages(): void
    {
        Route::get('/_navtest', fn () => view('components.navbar'))->name('about');

        $response = $this->get('/_navtest');

        $response->assertSee('sticky top-0 z-50', false);
        $response->assertDontSee('absolute inset-x-0 top-0 z-50', false);
    }
}
