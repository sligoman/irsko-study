<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BlogController;
use App\Http\Controllers\LeadController;

//welcome
Route::get('/welcome', function () {
   return 'Welcome to Laravel!';
});

Route::get('/', function () {
    return view('welcome');
});

// Static pages (blade views in resources/views/pages)
Route::view('/', 'pages.home')->name('home');
Route::view('/o-nas', 'pages.about')->name('about');
Route::view('/proc-irsko', 'pages.why-ireland')->name('why');
Route::view('/vysoke-skoly', 'pages.universities')->name('universities');
Route::view('/sluzby', 'pages.services')->name('services');
Route::view('/faq', 'pages.faq')->name('faq');
Route::view('/kontakt', 'pages.contact')->name('contact');
// Privacy policy (Czech)
Route::view('/ochrana-soukromi', 'pages.privacy-cs')->name('privacy.cs');
Route::get('/blog', [BlogController::class, 'index'])->name('blog');
Route::get('/blog/{slug}', [BlogController::class, 'show'])->name('blog.show');

// Serve universities JSON from storage so the frontend can fetch it from the same URL
Route::get('/img/universities/universities.json', function () {
    $path = storage_path('app/universities/universities.json');
    if (!file_exists($path)) {
        abort(404);
    }
    $content = file_get_contents($path);
    return response($content, 200)->header('Content-Type', 'application/json');
});

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Contact form POST handler (accepts JSON or form data) - use LeadController
Route::post('/contact', [LeadController::class, 'store'])->name('lead.store');
