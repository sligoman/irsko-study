<?php

use Illuminate\Support\Facades\Route;

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
Route::view('/blog', 'pages.blog')->name('blog');

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

// Contact form POST handler (accepts JSON or form data)
Route::post('/contact', function (Request $request) {
    $data = $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email|max:255',
        'message' => 'required|string',
    ]);

    // For now, log the submission. Replace with Mail or DB persisting as needed.
    Log::info('Contact form submission', $data);

    return response()->json(['message' => 'ok']);
});
