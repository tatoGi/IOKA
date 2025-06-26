<?php

use App\Http\Controllers\Admin\OffplanController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\MobileImageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
Route::get('/', function () {
    return redirect()->route('admin.dashboard');
});
Route::middleware('admin_auth')->group(function () {
    include_once 'admin/main.php';
    include_once 'admin/section.php';
    include_once 'admin/page.php';
    include_once 'admin/blogpost.php';
    include_once 'admin/postype.php';
    include_once 'admin/offplan.php';
    include_once 'admin/developer.php';
    Route::resource('ioka_admin/partners', PartnerController::class)->names('admin.partners');
    Route::delete('ioka_admin/partners/{id}/delete-image', [PartnerController::class, 'deleteImage'])->name('admin.partners.delete-image');

    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('offplan', OffplanController::class);
        Route::get('offplan/exterior-gallery', [OffplanController::class, 'exteriorGallery'])->name('offplan.exterior_gallery');
        Route::post('offplan/exterior-gallery', [OffplanController::class, 'storeExteriorGallery'])->name('offplan.exterior_gallery.store');
        Route::get('offplan/interior-gallery', [OffplanController::class, 'interiorGallery'])->name('offplan.interior_gallery');
        Route::post('offplan/interior-gallery', [OffplanController::class, 'storeInteriorGallery'])->name('offplan.interior_gallery.store');
    });
});
include_once 'admin/login.php';

include_once 'api.php';

// Add this new route for clearing caches (consider protecting it in production)
Route::get('/clear-cache', function() {
    Artisan::call('optimize:clear');
    return 'Cache cleared successfully!';
})->name('clear.cache');
Route::get('/create-storage-link', function() {
    try {
        // Remove existing link if it exists
        if (file_exists(public_path('storage'))) {
            unlink(public_path('storage'));
        }

        Artisan::call('storage:link');

        return response()->json([
            'success' => true,
            'message' => 'Storage link created successfully!',
            'output' => Artisan::output()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error creating storage link',
            'error' => $e->getMessage()
        ], 500);
    }
})->name('create.storage.link');

// Developer metadata image routes
Route::delete('admin/developer/{developer}/delete-og-image', [App\Http\Controllers\Admin\DeveloperController::class, 'deleteOgImage'])->name('admin.developer.delete-og-image');
Route::delete('admin/developer/{developer}/delete-twitter-image', [App\Http\Controllers\Admin\DeveloperController::class, 'deleteTwitterImage'])->name('admin.developer.delete-twitter-image');

// Blog post metadata image routes
Route::delete('admin/blogposts/{blogpost}/delete-og-image', [App\Http\Controllers\Admin\BlogPostController::class, 'deleteOgImage'])->name('admin.blogposts.delete-og-image');
Route::delete('admin/blogposts/{blogpost}/delete-twitter-image', [App\Http\Controllers\Admin\BlogPostController::class, 'deleteTwitterImage'])->name('admin.blogposts.delete-twitter-image');

// Rental/Resale metadata image routes
Route::delete('admin/rental_resale/{postype}/delete-og-image', [App\Http\Controllers\Admin\PostypeController::class, 'deleteOgImage'])->name('admin.rental_resale.delete-og-image');
Route::delete('admin/rental_resale/{postype}/delete-twitter-image', [App\Http\Controllers\Admin\PostypeController::class, 'deleteTwitterImage'])->name('admin.rental_resale.delete-twitter-image');

// Mobile image upload route
Route::post('/api/mobile-image-upload', [MobileImageController::class, 'upload'])->name('mobile.image.upload');
