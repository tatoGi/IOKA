<?php

use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

Route::resource('/ioka_admin/menu', PageController::class);
Route::post('/pages/arrange', [PageController::class, 'arrange'])->name('pages.arrange');

Route::get('/ioka_admin/messages', [MessageController::class, 'index'])->name('messages.index');
