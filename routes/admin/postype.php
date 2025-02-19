<?php
    use Illuminate\Support\Facades\Route;
    use App\Http\Controllers\Admin\PostypeController;


    Route::group(['prefix' => 'ioka_admin/postypes', 'as' => 'admin.postypes.'], function () {

        Route::get('/rental_resale', [PostypeController::class, 'rentalindex'])->name('rental.index');
        Route::get('/offplan', [PostypeController::class, 'offplanindex'])->name('offplan.index');
        Route::get('/rental_resale/create', [PostypeController::class, 'rentalcreate'])->name('rental_resale.create');
        Route::post('/rental_resale/store', [PostypeController::class, 'rentalstore'])->name('rental_resale.store');
        Route::put('/rental_resale/update/{postype}', [PostypeController::class, 'update'])->name('rental_resale.update');
        Route::get('/rental_resale/{postype}/edit', [PostypeController::class, 'rentaledit'])->name('rental_resale.edit');
        Route::delete('/rental_resale/{postype}', [PostypeController::class, 'rentaldestroy'])->name('rental_resale.destroy');
        Route::delete('/rental_resale/{postype}/remove-qr-photo', [PostypeController::class, 'removeQrPhoto'])->name('rental_resale.removeQrPhoto');
        Route::delete('/rental_resale/{postype}/remove-gallery-image', [PostypeController::class, 'removeGalleryImage'])->name('rental_resale.removeGalleryImage');
        Route::get('/rental_resale/{postype}/gallery-images', [PostypeController::class, 'getGalleryImages'])->name('rental_resale.getGalleryImages');
        Route::post('/rental_resale/{postype}/upload-gallery-images', [PostypeController::class, 'uploadGalleryImages'])->name('rental_resale.uploadGalleryImages');
    });
