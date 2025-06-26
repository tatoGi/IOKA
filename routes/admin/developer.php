<?php

use App\Http\Controllers\Admin\DeveloperController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ioka_admin/developer', 'as' => 'admin.developer.'], function () {
    Route::get('/list', [DeveloperController::class, 'index'])->name('list');
    Route::get('/create', [DeveloperController::class, 'create'])->name('create');
    Route::post('/store', [DeveloperController::class, 'store'])->name('store');
    Route::get('/edit/{id}', [DeveloperController::class, 'edit'])->name('edit');
    Route::post('/update/{id}', [DeveloperController::class, 'update'])->name('update');
    Route::delete('/delete/{id}', [DeveloperController::class, 'destroy'])->name('delete');
    Route::post('/delete-photo', [DeveloperController::class, 'deletePhoto'])->name('delete.photo');
    Route::post('/delete-award', [DeveloperController::class, 'deleteAward'])->name('admin.developer.delete-award');
    Route::post('/{id}/delete-mobile-photo', [DeveloperController::class, 'deleteMobilePhoto'])->name('delete-mobile-photo');
    Route::post('/{id}/delete-mobile-logo', [DeveloperController::class, 'deleteMobileLogo'])->name('delete-mobile-logo');
    Route::post('/{id}/delete-mobile-banner-image', [DeveloperController::class, 'deleteMobileBannerImage'])->name('delete-mobile-banner-image');
});
