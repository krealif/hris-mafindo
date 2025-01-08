<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\LetterReviewController;
use App\Http\Controllers\RegistrationReviewController;
use App\Http\Controllers\UserMigrationController;
use App\Http\Controllers\UserRegistrationController;
use Illuminate\Support\Facades\Route;

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
        Route::get('permohonan', 'index')->name('index');
        Route::get('log', 'indexLog')->name('indexLog');
        Route::delete('bulk-delete', 'bulkDelete')->name('bulkDelete');

        Route::get('permohonan/{registration}', 'show')->name('show');
        Route::patch('permohonan/{registration}/next', 'nextStep')->name('nextStep');
        Route::patch('permohonan/{registration}/revisi', 'requestRevision')->name('requestRevision');
        Route::patch('permohonan/{registration}/selesai', 'approveRegistration')->name('approve');
        Route::patch('permohonan/{registration}/tolak', 'rejectRegistration')->name('reject');
        Route::delete('permohonan/{registration}', 'destroy')->name('destroy');
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

    // Group of routes related to "Surat", accessible by admin, relawan, and pengurus
    // The actions users can perform are determined by their role
    // with permission checks done in the controller, model policy or views
    Route::group([
        'controller' => LetterController::class,
        'as' => 'surat.',
        'prefix' => 'surat',
    ], function () {
        Route::get('kotak-surat', 'indexLetter')->name('letterbox');
        Route::get('permohonan-wilayah', 'indexByWilayah')->name('indexWilayah');

        Route::get('permohonan/buat', 'create')->name('create');
        Route::post('permohonan/buat', 'store')->name('store');

        Route::get('permohonan/{letter}', 'show')->name('show');
        Route::get('permohonan/{letter}/edit', 'edit')->name('edit');
        Route::patch('permohonan/{letter}', 'update')->name('update');
        Route::delete('permohonan/{letter}', 'destroy')->name('destroy');
        Route::get('permohonan/{letter}/download', 'download')->name('download');
        Route::get('permohonan/{letter}/attachment', 'downloadAttachment')->name('downloadAttachment');
    });

    // Group of routes for admin to handle letter application
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => LetterReviewController::class,
        'as' => 'surat.',
        'prefix' => 'surat',
    ], function () {
        Route::get('permohonan', 'indexSubmission')->name('index');
        Route::get('histori', 'indexHistory')->name('indexHistory');
        Route::patch('permohonan/{letter}/upload', 'uploadResult')->name('uploadResult');
        Route::patch('permohonan/{letter}/revisi', 'requestRevision')->name('requestRevision');
        Route::patch('permohonan/{letter}/kirim', 'approveSubmission')->name('approve');
        Route::patch('permohonan/{letter}/tolak', 'rejectSubmission')->name('reject');
        Route::delete('bulk-delete', 'bulkDelete')->name('bulkDelete');
    });
});
