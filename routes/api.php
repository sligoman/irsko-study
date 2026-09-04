<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\InstagramController;
use App\Http\Controllers\Api\LeadReviewController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::get('/instagram', [InstagramController::class, 'index']);
Route::get('/instagram/feed', [InstagramController::class, 'feed']);

Route::middleware(['auth:sanctum'])->prefix('internal/leads')->group(function (): void {
    Route::get('/pending', [LeadReviewController::class, 'index']);
    Route::get('/report', [LeadReviewController::class, 'report']);
    Route::patch('/{lead}/review', [LeadReviewController::class, 'update']);
});
