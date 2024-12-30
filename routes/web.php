<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});
Route::middleware('admin_auth')->group(function () {
    include_once 'admin/main.php';
    include_once 'admin/page.php';
});
include_once 'admin/login.php';