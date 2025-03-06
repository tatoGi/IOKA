<?php

use App\Http\Controllers\Website\FrontendController;
use Illuminate\Support\Facades\Route;

Route::get('/pages', [FrontendController::class, 'getPages']);
Route::get('/pages/{slug}', [FrontendController::class, 'getPage']);
Route::get('/sections', [FrontendController::class, 'getSections']);
Route::get('/sections/{id}', [FrontendController::class, 'getSection']);
Route::get('/blogs', [FrontendController::class, 'getBlogs']);
