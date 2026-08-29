<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LeadController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\FinderController;
use App\Http\Controllers\PageController;

// Security token route - sets session so testing environment can be viewed
Route::get('/token', [PageController::class, 'setToken'])->name('token');

// Static pages (blade views in resources/views/pages)
Route::view('/', 'pages.home')->name('home');
Route::view('/o-nas', 'pages.about')->name('about');
Route::view('/proc-irsko', 'pages.why-ireland')->name('why');
Route::get('/vysoke-skoly', [FinderController::class, 'universities'])->name('universities');
Route::view('/sluzby', 'pages.services')->name('services');
Route::get('/faq', [FaqController::class, 'index'])->name('faq');
Route::view('/kontakt', 'pages.contact')->name('contact');
// Privacy policy (Czech)
Route::view('/ochrana-soukromi', 'pages.privacy-cs')->name('privacy.cs');
Route::get('/prakticky-pruvodce', [BlogController::class, 'index'])->defaults('contentType', 1)->name('blog');
Route::get('/novinky', [BlogController::class, 'index'])->defaults('contentType', 2)->name('news');
Route::get('/prakticky-pruvodce/{slug}', [BlogController::class, 'show'])->name('blog.show');
Route::permanentRedirect('/blog', '/prakticky-pruvodce');
Route::permanentRedirect('/blog/{slug}', '/prakticky-pruvodce/{slug}');


// Sitemap for search engines (XML) and a human-readable sitemap page
use App\Http\Controllers\SitemapController;

Route::get('/sitemap.xml', [SitemapController::class, 'xml']);
Route::get('/sitemap', [SitemapController::class, 'page'])->name('sitemap');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Contact form POST handler (accepts JSON or form data) - use LeadController
Route::post('/contact', [LeadController::class, 'store'])->middleware('throttle:leads')->name('lead.store');

// Finder - schools & courses (uses models from sligoman/caofinder package)
// Czech-friendly routes:
// - Universities listing: /vysoke-skoly (already defined above as 'universities')
// - University profile: /vysoke-skoly/{url}
// - Courses listing: /kurzy
// - Course detail: /kurzy/{url}
// - Course search: /kurzy/hledat
Route::get('/vysoke-skoly/{url}', [FinderController::class, 'showSchool'])->name('finder.school.show');
// Define specific routes first so they don't get captured by the generic {url} route.
// Search/filter endpoint for courses (Czech)
Route::get('/kurzy/hledat', [FinderController::class, 'search'])->name('finder.search');
Route::get('/kurzy', [FinderController::class, 'courses'])->name('finder.courses');
// Generic course detail route (must come after the specific routes)
Route::get('/kurzy/{url}', [FinderController::class, 'showCourse'])->name('finder.course.show');

// Backwards-compatibility redirects from legacy English paths to Czech paths
// These help avoid 404s for any cached/compiled frontend assets still calling the old endpoints.
Route::permanentRedirect('/finder/schools', '/vysoke-skoly');
Route::permanentRedirect('/finder/schools/{any}', '/vysoke-skoly/{any}')->where('any', '.*');
Route::permanentRedirect('/finder/courses', '/kurzy');
Route::permanentRedirect('/finder/courses/{any}', '/kurzy/{any}')->where('any', '.*');
Route::permanentRedirect('/finder/search', '/kurzy/hledat');
