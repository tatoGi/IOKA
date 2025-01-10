<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\PartnerController;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('admin_auth')->group(function () {
    include_once 'admin/main.php';
    include_once 'admin/section.php';
    include_once 'admin/page.php';
    include_once 'admin/blogpost.php';
    Route::resource('ioka_admin/partners', PartnerController::class)->names('admin.partners');
    Route::delete('/admin/partners/{partner}/delete-image', [PartnerController::class, 'deleteImage']);
});
include_once 'admin/login.php';
include_once 'api.php';
