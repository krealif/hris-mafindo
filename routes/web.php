<?php

use Illuminate\Support\Facades\Route;
use Spatie\Honeypot\ProtectAgainstSpam;
use App\Http\Controllers\RegistrationController;

// User registration
Route::controller(RegistrationController::class)->group(function () {
    Route::get('/register', 'create')->name('register');
    Route::post('/register', 'store')->name('register.store')
        ->middleware(ProtectAgainstSpam::class);
    Route::get('/register-success', 'success')->name('register.success');
});

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('hris.dashboard');
    });
});
