<?php

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Admin\MessageController;
use App\Http\Controllers\Admin\SectionController;

Route::resource('/ioka_admin/menu', PageController::class);
Route::post('/pages/arrange', [PageController::class, 'arrange'])->name('pages.arrange');

Route::get('/ioka_admin/messages', [MessageController::class, 'index'])->name('messages.index');
