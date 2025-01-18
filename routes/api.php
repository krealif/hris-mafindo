<?php

use App\Http\Controllers\Api\UserController;
use Illuminate\Support\Facades\Route;

Route::middleware(['web', 'auth', 'approved'])->group(function () {
    Route::group([
        'controller' => UserController::class,
        'as' => 'userApi.',
    ], function () {
        Route::get('users', 'getAll')->name('getAll');
        Route::get('users/kegiatan/{event}/relawan', 'getRelawanForCertificate')->name('getRelawanForCertificate');
    });
});
