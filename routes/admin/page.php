<?php

use App\Http\Controllers\Admin\PageController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;


Route::resource('/ioka_admin/menu', PageController::class);
Route::post('/pages/arrange', [PageController::class, 'arrange'])->name('pages.arrange');
