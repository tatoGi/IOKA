<?php

use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PageController;
use App\Http\Controllers\Admin\SettingsController;
use Illuminate\Support\Facades\Route;

Route::resource('/ioka_admin/menu', PageController::class);
Route::post('/pages/arrange', [PageController::class, 'arrange'])->name('pages.arrange');

Route::get('/ioka_admin/messages', [MessageController::class, 'index'])->name('admin.messages.index');
Route::get('/ioka_admin/messages/{message}', [MessageController::class, 'show'])->name('admin.messages.show');
Route::delete('/ioka_admin/messages/{message}', [MessageController::class, 'destroy'])->name('admin.messages.destroy');
Route::get('/ioka_admin/subscribe', [MessageController::class, 'subscribe_index'])->name('admin.subscribe.index');
Route::get('/ioka_admin/subscribe/{message}', [MessageController::class, 'subscribe_show'])->name('admin.subscribe.show');
Route::delete('/ioka_admin/subscribe/{message}', [MessageController::class, 'subscribe_destroy'])->name('admin.subscribe.destroy');
Route::get('/ioka_admin/settings', [SettingsController::class, 'index'])->name('admin.settings.index');
Route::put('/ioka_admin/settings', [SettingsController::class, 'update'])->name('admin.settings.update');
Route::post('/ioka_admin/settings/delete-meta-image', [SettingsController::class, 'deleteMetaImage'])->name('admin.settings.delete-meta-image');
Route::post('/ioka_admin/settings/delete-logo', [SettingsController::class, 'deleteLogo'])->name('admin.settings.delete-logo');
