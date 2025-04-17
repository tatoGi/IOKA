<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\PolicyPageController;
use Illuminate\Support\Facades\Route;

Route::get('/ioka_admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/ioka_admin/activity', [AdminController::class, 'activity'])->name('admin.activity');
Route::redirect('/ioka_admin', '/ioka_admin/menu');
Route::get('/ioka_admin/locations', [LocationController::class, 'index'])->name('admin.locations');
Route::get('/ioka_admin/locations/create', [LocationController::class, 'create'])->name('admin.locations.create');
Route::get('/ioka_admin/locations/{location}/edit', [LocationController::class, 'edit'])->name('admin.locations.edit');
Route::delete('/ioka_admin/locations/{location}/delete', [LocationController::class, 'destroy'])->name('admin.locations.destroy');
Route::post('/ioka_admin/locations/store', [LocationController::class, 'store'])->name('admin.locations.store');
Route::put('/ioka_admin/locations/{location}/update', [LocationController::class, 'update'])->name('admin.locations.update');
Route::resource('/ioka_admin/policy-pages', PolicyPageController::class)
    ->only(['index', 'edit', 'update'])
    ->names([
        'index' => 'admin.policy-pages.index',
        'edit' => 'admin.policy-pages.edit',
        'update' => 'admin.policy-pages.update'
    ]);
    Route::resource('ioka_admin/faqs', FaqController::class)->names('admin.faq');
