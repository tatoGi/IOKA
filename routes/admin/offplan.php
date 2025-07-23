<?php

use App\Http\Controllers\Admin\OffplanController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ioka_admin/offplan', 'as' => 'admin.offplan.'], function () {
    Route::resource('offplan', OffplanController::class);
    Route::post('{offplan}/delete-image', [OffplanController::class, 'deleteImage'])->name('delete-image');
    Route::delete('{offplan}/delete-og-image', [OffplanController::class, 'deleteOgImage'])->name('delete-og-image');
    Route::delete('{offplan}/delete-twitter-image', [OffplanController::class, 'deleteTwitterImage'])->name('delete-twitter-image');
});
