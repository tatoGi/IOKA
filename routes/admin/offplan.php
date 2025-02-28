<?php

use App\Http\Controllers\Admin\OffplanController;
use Illuminate\Support\Facades\Route;

Route::group(['prefix' => 'ioka_admin/offplan', 'as' => 'admin.offplan.'], function () {
    Route::resource('offplan', OffplanController::class);
});
