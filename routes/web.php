<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LetterReviewController;
use App\Http\Controllers\UserMigrationController;
use App\Http\Controllers\LetterTemplateController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\RegistrationReviewController;

/**
 * Group of routes that require authentication but for unverified users only.
 */
Route::middleware(['auth', 'unverified'])->group(function () {
    // Routes for user registration forms.
    Route::group([
        'controller' => UserRegistrationController::class,
        'as' => 'registrasi.',
        'prefix' => 'registrasi',
    ], function () {
        Route::get('form', 'selectForm')->name('selectForm');
        Route::get('form/{type}', 'showForm')->name('showForm');
        Route::post('form/{type}', 'store')->name('store');
    });
});

/**
 * Group of routes that require authentication and verified registrations by admin.
 */
Route::middleware(['auth', 'verified'])->group(function () {
    // Home route for the dashboard.
    Route::get('/', HomeController::class)->name('home');

    // Group of routes for admin to manage & review user registration submissions
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => RegistrationReviewController::class,
        'as' => 'registrasi.',
        'prefix' => 'registrasi',
    ], function () {
        Route::get('ajuan', 'index')->name('index');
        Route::get('log', 'indexLog')->name('indexLog');
        Route::delete('prune', 'prune')->name('prune');

        Route::get('ajuan/{registration}', 'show')->name('show');
        Route::patch('ajuan/{registration}/next', 'nextStep')->name('nextStep');
        Route::patch('ajuan/{registration}/revisi', 'requestRevision')->name('revisi');
        Route::patch('ajuan/{registration}/selesai', 'finishRegistration')->name('finish');
        Route::patch('ajuan/{registration}/tolak', 'rejectRegistration')->name('reject');
        Route::delete('ajuan/{registration}', 'destroy')->name('destroy');
    });

    // Group of routes for admin to migrate old users data into the system
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => UserMigrationController::class,
        'as' => 'migrasi.',
        'prefix' => 'registrasi',
    ], function () {
        Route::get('migrasi', 'index')->name('index');
        Route::get('migrasi/tambah', 'create')->name('create');
        Route::get('migrasi/{tempUser}/edit', 'edit')->name('edit');
        Route::post('migrasi', 'store')->name('store');
        Route::patch('migrasi/{tempUser}', 'update')->name('update');
        Route::delete('migrasi/{tempUser}', 'destroy')->name('destroy');
    });

    // Letter template
    Route::get('surat/ajuan/template', LetterTemplateController::class)->name('surat.template');

    // Group of routes related to "Surat", accessible by admin, relawan, and pengurus
    // The actions users can perform are determined by their role
    // with permission checks done in the controller and views
    Route::group([
        'controller' => LetterController::class,
        'as' => 'surat.',
        'prefix' => 'surat',
    ], function () {
        Route::get('ajuan-saya', 'index')->name('index');
        Route::get('ajuan-wilayah', 'indexByWilayah')->name('indexWilayah');
        Route::get('ajuan/buat/{template:view}', 'create')->name('create');
        Route::post('ajuan/buat/{template:view}', 'store')->name('store');

        Route::get('ajuan/{letter}', 'show')->name('show');
        Route::get('ajuan/{letter}/edit', 'edit')->name('edit');
        Route::post('ajuan/{letter}/edit', 'update')->name('update');
        Route::delete('ajuan/{letter}', 'destroy')->name('destroy');
        Route::get('ajuan/{letter}/download', 'download')->name('download');
    });

    Route::group([
        'controller' => LetterReviewController::class,
        'as' => 'surat.rev.',
        'prefix' => 'surat',
    ], function () {
        Route::get('ajuan', 'index')->name('index');
        Route::get('ajuan/{letter}/review', 'review')->name('review');
        Route::patch('{letter}/upload', 'upload')->name('upload');
    });
});
