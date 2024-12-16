<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RegistrationController;
use App\Http\Controllers\RegistrationMigrateController;
use App\Http\Controllers\RegistrationVerifController;

/**
 * Group routes that require authentication but for unverified users only.
 */
Route::middleware(['auth', 'unverified'])->group(function () {
    // Routes for user registration forms.
    Route::group([
        'controller' => RegistrationController::class,
        'as' => 'registration.',
        'prefix' => 'registrasi'
    ], function () {
        Route::get('form', 'selectForm')->name('selectForm');
        Route::get('form/{type}', 'showForm')->name('showForm');
        Route::post('form/{type}', 'store')->name('store');
    });
});

/**
 * Group routes that require authentication and verified registrations by admin.
 */
Route::middleware(['auth', 'verified'])->group(function () {
    // Home route for the dashboard.
    Route::get('/', HomeController::class)->name('home');

    // Group routes for admin-specific registration verification tasks.
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => RegistrationVerifController::class,
        'as' => 'verif.',
        'prefix' => 'registrasi'
    ], function () {
        Route::get('akun', 'index')->name('index');
        Route::get('riwayat', 'indexHistory')->name('history');

        Route::get('akun/{registration}', 'show')->name('show');
        Route::patch('akun/{registration}/next', 'nextStep')->name('nextStep');
        Route::patch('akun/{registration}/revisi', 'requestRevision')->name('revisi');
        Route::patch('akun/{registration}/selesai', 'finishRegistration')->name('finish');
        Route::patch('akun/{registration}/tolak', 'rejectRegistration')->name('reject');
        Route::delete('akun/{registration}', 'destroy')->name('destroy');
    });

    // Group routes for admin-specific registration verification tasks.
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => RegistrationMigrateController::class,
        'as' => 'migrasi.',
        'prefix' => 'registrasi'
    ], function () {
        Route::get('migrasi', 'index')->name('index');
        Route::get('migrasi/tambah', 'create')->name('create');
        Route::get('migrasi/{user}/edit', 'edit')->name('edit');
        Route::post('migrasi', 'store')->name('store');
        Route::patch('migrasi/{user}', 'update')->name('update');
        Route::delete('migrasi/{user}', 'destroy')->name('destroy');
    });
});
