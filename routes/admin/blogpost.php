<?php

use App\Http\Controllers\Admin\BlogPostController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'blogposts', 'as' => 'blogposts.'], function () {
    Route::get('/ioka_admin', [BlogPostController::class, 'index'])->name('index');
    Route::get('/ioka_admin/create', [BlogPostController::class, 'create'])->name('create');
    Route::post('/ioka_admin', [BlogPostController::class, 'store'])->name('store');
    Route::get('/ioka_admin/{blogPost}/edit', [BlogPostController::class, 'edit'])->name('edit');
    Route::put('/{blogPost}', [BlogPostController::class, 'update'])->name('update');
    Route::delete('/{blogPost}', [BlogPostController::class, 'destroy'])->name('destroy');
    Route::delete('/{blogPost}/remove-image', [BlogPostController::class, 'removeImage'])->name('removeImage');
});
