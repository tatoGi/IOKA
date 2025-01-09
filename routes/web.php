<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SectionController;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('admin_auth')->group(function () {
    include_once 'admin/main.php';
    include_once 'admin/section.php';
    include_once 'admin/page.php';
    include_once 'admin/blogpost.php';
});
include_once 'admin/login.php';
include_once 'api.php';
