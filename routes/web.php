<?php

use App\Models\Branch;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;

/**
 * Route for user registration detail
 */
Route::middleware(['auth', 'unverified'])->group(function () {
    Route::group([
        'controller' => RegistrationController::class,
        'as' => 'registration.',
        'prefix' => 'register'
    ], function () {
        Route::get('user', 'selectForm')->name('selectForm');
        Route::get('user/{type}', 'showForm')->name('showForm');
        Route::post('user/{type}', 'store')->name('store');
    });
});

Route::get('labx', function () {
    $branches = Branch::all()->pluck('nama', 'id');
});

// Dashboard
Route::middleware(['auth', 'verified'])->group(function () {
    // Home
    Route::get('/', HomeController::class);

    Route::view('tes', 'hris.materi.materi');

    // Registration
    // Route::group([
    //     'middleware' => ['role:admin'],
    //     'controller' => RegistrationController::class,
    //     'as' => 'registration.',
    //     'prefix' => 'pendaftaran'
    // ], function () {
    //     Route::get('/', 'index')->name('index')
    //         ->middleware('preserveUrlQuery');

    //     Route::post('{registration}/accept', 'accept')->name('accept');
    //     Route::post('{registration}/reject', 'reject')->name('reject');
    // });
});
