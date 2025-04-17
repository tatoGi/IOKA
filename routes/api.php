<?php

use App\Http\Controllers\Website\FrontendController;
use App\Http\Controllers\Website\PropertySearchController;
use App\Http\Controllers\Website\SubscriptionController;
use Illuminate\Support\Facades\Route;


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
    Route::get('/about/{id}', [FrontendController::class, 'getabout']);
    Route::get('/contact/{id}', [FrontendController::class, 'getContact']);
    Route::post('/contact/submissions', [FrontendController::class, 'submission'])->withoutMiddleware(['csrf']);
    Route::get('/search', [FrontendController::class, 'search']);
    Route::get('/offplans_filter', [FrontendController::class, 'filter_offplan']);
    Route::get('/rental_filter', [FrontendController::class, 'filter_rentals']);
    Route::get('/search_for_homes', [FrontendController::class, 'search_for_homes']);
    Route::get('/locations', [FrontendController::class, 'getLocations']);
    Route::get('/properties/Search', [PropertySearchController::class, 'search']);
    Route::post('/subscribe', [SubscriptionController::class, 'subscribe'])->withoutMiddleware(['csrf']);
    Route::get('/subscribe/verify/{token}', [SubscriptionController::class, 'verify']);
    Route::get('/settings', [FrontendController::class, 'getSettings']);
    Route::get('/developers_search', [FrontendController::class, 'searchDeveloper']);
    Route::get('/rental/related', [FrontendController::class, 'getRelatedRental']);
    Route::get('/policies', [FrontendController::class, 'policy']);
    Route::get('/faqs', [FrontendController::class, 'getFAQ']);

