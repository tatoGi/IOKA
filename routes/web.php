<?php

use App\Http\Controllers\Admin\OffplanController;
use App\Http\Controllers\Admin\PartnerController;
use App\Http\Controllers\Admin\SettingsController;
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
    Route::delete('/admin/partners/{partner}/delete-image', [PartnerController::class, 'deleteImage']);

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
