<?php

use App\Http\Controllers\Admin\AuthController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

// Custom login route
Route::get('/ioka-admin-login', [AuthController::class, 'index'])->name('admin.login');
Route::post('/ioka-admin-login', [AuthController::class, 'login'])->name('admin.login.submit')->middleware('throttle:100,1');
Route::post('/logout', function () {
    Auth::logout(); // Logs out the current user

    return redirect()->route('admin.login'); // Redirects to admin login page
})->name('logout');
