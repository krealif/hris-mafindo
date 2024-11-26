<?php

use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LetterSubmissionController;
use App\Http\Controllers\RegistrationController;

// User registration
// Route::controller(RegistrationController::class)->group(function () {
//     Route::get('register', 'create')->name('register');
//     Route::post('register', 'store')->name('register.store')
//         ->middleware(ProtectAgainstSpam::class);

//     Route::get('register-success', 'success')->name('register.success');
// });

// User registration
Route::middleware(['auth', 'unverified'])->group(function () {
    // Registration
    Route::group([
        'controller' => RegistrationController::class,
        'as' => 'registration.',
        'prefix' => 'register'
    ], function () {
        Route::get('form', 'selectForm')->name('selectForm');
        Route::get('form/{type}', 'showForm')->name('showForm');
        Route::post('form/{type}', 'store')->name('store');
    });
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
