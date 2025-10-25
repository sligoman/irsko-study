<?php

use Illuminate\Support\Facades\Route;

//welcome
Route::get('/welcome', function () {
   return 'Welcome to Laravel!';
});

Route::get('/', function () {
    dd('test');
    return view('welcome');
});
