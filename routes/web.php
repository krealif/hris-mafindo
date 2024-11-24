<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\LetterTemplateController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\RegistrationController;

// User registration
Route::controller(RegistrationController::class)->group(function () {
    Route::get('register', 'create')->name('register');
    Route::post('register', 'store')->name('register.store')
        ->middleware(ProtectAgainstSpam::class);

    Route::get('register-success', 'success')->name('register.success');
});

// Dashboard
Route::middleware('auth')->group(function () {
    // Home
    Route::get('/', function () {
        return view('hris.home');
    });

    // Registration
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => RegistrationController::class,
        'as' => 'registration.',
        'prefix' => 'pendaftaran'
    ], function () {
        Route::get('/', 'index')->name('index')
            ->middleware('preserveUrlQuery');

        Route::post('{registration}/accept', 'accept')->name('accept');
        Route::post('{registration}/reject', 'reject')->name('reject');
    });

    // Letter template
    Route::get('persuratan/buat', LetterTemplateController::class)->name('letter.template');

    // Letter
    Route::group([
        'controller' => LetterController::class,
        'as' => 'letter.',
        'prefix' => 'persuratan'
    ], function () {
        Route::get('/', 'index')->name('index');
        Route::get('buat/{letterTemplate:view}', 'create')->name('create');
        Route::post('buat/{letterTemplate:view}', 'store')->name('store');

        Route::get('{letter}/edit', 'edit')->name('edit');
        Route::post('{letter}/edit', 'update')->name('update');

        Route::get('{letter}', 'show')->name('show');
        Route::get('{letter}/review', 'review')->name('review');
        Route::post('{letter}/upload', 'upload')->name('upload');
        Route::get('{letter}/download', 'download')->name('download');
    });
});
