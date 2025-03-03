<?php

use App\Http\Controllers\Admin\SectionController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ioka_admin/pages/{pageId}/sections', 'as' => 'admin.sections.'], function () {
    Route::get('/', [SectionController::class, 'index'])->name('index');
    Route::get('create/{sectionKey}', [SectionController::class, 'create'])->name('create');
    Route::post('/', [SectionController::class, 'store'])->name('store');
    Route::get('{sectionKey}/edit', [SectionController::class, 'edit'])->name('edit');
    Route::put('{sectionKey}', [SectionController::class, 'update'])->name('update');
    Route::post('reorder', [SectionController::class, 'reorder'])->name('reorder');
    Route::delete('{sectionKey}', [SectionController::class, 'destroy'])->name('destroy');
    Route::delete('{sectionKey}/delete-image', [SectionController::class, 'deleteImage'])->name('delete_image');
});
