<?php

use App\Http\Controllers\Website\FrontendController;
use Illuminate\Support\Facades\Route;

Route::group(['middleware' => 'cors'], function () {
    Route::get('/pages', [FrontendController::class, 'getPages']);
    Route::get('/pages/{slug}', [FrontendController::class, 'getPage']);
    Route::get('/sections', [FrontendController::class, 'getSections']);
    Route::get('/sections/{id}', [FrontendController::class, 'getSection']);
    Route::get('/blogs', [FrontendController::class, 'getBlogs']);
    Route::get('/blogs/{slug}', [FrontendController::class, 'getBlog']);
    Route::get('/developers', [FrontendController::class, 'getdevelopers']);
    Route::get('/developers/{slug}', [FrontendController::class, 'getDeveloper']);
    Route::get('/offplans', [FrontendController::class, 'getOffplans']);
    Route::get('/offplans/{slug}', [FrontendController::class, 'getOffplan']);
    Route::get('/rental_resales', [FrontendController::class, 'getRentalResale']);
    Route::get('/rental_resales/{slug}', [FrontendController::class, 'getRentalResaleBySlug']);
    Route::get('/partners', [FrontendController::class, 'getPartners']);
});
