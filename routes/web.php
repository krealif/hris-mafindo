<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\RegistrationController;

// User registration
Route::controller(RegistrationController::class)->group(function () {
    Route::get('/register', 'create')->name('register');
    Route::get('/register-success', 'success')->name('register.success');
    Route::post('/register', 'store')->name('register.store')
        ->middleware(ProtectAgainstSpam::class);
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
        'as' => 'registration.'
    ], function () {
        Route::get('/pendaftaran', 'index')->name('index');
        Route::post('/pendaftaran/{registration}/approve', 'approve')->name('approve');
        Route::post('/pendaftaran/{registration}/reject', 'reject')->name('reject');
    });
});
