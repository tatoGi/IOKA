<?php

use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;

Route::get('/ioka_admin', [AdminController::class, 'index'])->name('admin.dashboard');
Route::get('/ioka_admin/activity', [AdminController::class, 'activity'])->name('admin.activity');
Route::redirect('/ioka_admin', '/ioka_admin/menu');