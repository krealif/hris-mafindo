<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\EventController;
use App\Http\Controllers\BranchController;
use App\Http\Controllers\LetterController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\UserSettingController;
use App\Http\Controllers\LetterReviewController;
use App\Http\Controllers\UserMigrationController;
use App\Http\Controllers\EventCertificateController;
use App\Http\Controllers\UserRegistrationController;
use App\Http\Controllers\RegistrationReviewController;

/**
 * Group of routes that require authentication but for unapproved users only.
 */
Route::middleware(['auth', 'unapproved'])->group(function () {
    // Route registrasi relawan dan pengurus.
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
Route::middleware(['auth', 'approved'])->group(function () {
    // Route utama untuk dashboard setelah login dan disetujui.
    Route::get('/', HomeController::class)->name('home');

    // Grup route untuk manajemen data pengguna dan relawan.
    Route::group([
        'controller' => UserController::class,
        'as' => 'user.',
    ], function () {
        Route::get('data/pengguna', 'index')->name('index');
        Route::get('data/relawan', 'indexWilayah')->name('indexWilayah');
        Route::get('data/pengguna/ekspor-relawan', 'exportRelawan')->name('exportRelawan');
        Route::get('data/pengguna/ekspor-pengurus', 'exportPengurus')->name('exportPengurus');
    });

    // Grup route terkait menampilkan profil dan edit profil pengguna.
    Route::group([
        'controller' => UserProfileController::class,
        'as' => 'user.',
    ], function () {
        Route::get('pengaturan', 'settings')->name('settings');

        Route::get('profil/edit', 'editProfile')->name('editProfile');
        Route::get('profil/{user}/edit', 'editProfile')->name('editProfileById');
        Route::get('profil/sertifikat', 'listCertificates')->name('certificate');
        Route::get('profil/{user}/sertifikat', 'listCertificates')->name('certificateById');
        Route::get('profil/{user?}', 'profile')->name('profile');
        Route::patch('profil/{user?}', 'updateProfile')->name('updateProfile');
    });

    // Grup route terkait pengaturan pengguna.
    Route::group([
        'controller' => UserSettingController::class,
        'as' => 'user.',
    ], function () {
        Route::get('pengaturan', 'settings')->name('settings');
        Route::patch('pengaturan/update-email', 'updateEmail')->name('updateEmail');
        Route::patch('pengaturan/update-password', 'updatePassword')->name('updatePassword');
    });

    // Grup route untuk admin dalam mengelola data wilayah (branch).
    Route::group([
        'middleware' => ['role:admin'],
        'controller' => BranchController::class,
        'as' => 'wilayah.',
        'prefix' => 'data',
    ], function () {
        Route::get('wilayah', 'index')->name('index');
        Route::get('wilayah/ekspor', 'export')->name('export');
        Route::get('wilayah/tambah', 'create')->name('create');
        Route::post('wilayah', 'store')->name('store');
        Route::get('wilayah/{branch}', 'show')->name('show');
        Route::get('wilayah/{branch}/edit', 'edit')->name('edit');
        Route::patch('wilayah/{branch}', 'update')->name('update');
        Route::delete('wilayah/{branch}', 'destroy')->name('destroy');
    });

    // Grup route untuk admin dalam meninjau dan menangani permohonan registrasi.
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
        Route::patch('permohonan/{registration}/selesai', 'approve')->name('approve');
        Route::patch('permohonan/{registration}/tolak', 'reject')->name('reject');
        Route::delete('permohonan/{registration}', 'destroy')->name('destroy');
    });

    // Grup route untuk admin dalam migrasi data pengguna lama.
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

    // Grup route yang berkaitan dengan "Surat" (dapat diakses oleh admin, relawan, dan pengurus).
    // Tindakan yang dapat dilakukan pengguna ditentukan oleh role.
    // Terdapat juga pengecekan permission yang dilakukan di controller, model policy atau views.
    Route::group([
        'controller' => LetterController::class,
        'as' => 'surat.',
        'prefix' => 'surat',
    ], function () {
        Route::get('kotak-surat', 'indexLetterBox')->name('letterbox');
        Route::get('permohonan-wilayah', 'indexByWilayah')->name('indexWilayah');

        Route::get('permohonan/buat', 'create')->name('create');
        Route::post('permohonan', 'store')->name('store');

        Route::get('permohonan/{letter}', 'show')->name('show');
        Route::get('permohonan/{letter}/edit', 'edit')->name('edit');
        Route::patch('permohonan/{letter}', 'update')->name('update');
        Route::delete('permohonan/{letter}', 'destroy')->name('destroy');
        Route::get('permohonan/{letter}/download', 'download')->name('download');
        Route::get('permohonan/{letter}/attachment', 'downloadAttachment')->name('downloadAttachment');
    });

    // Grup route untuk admin dalam meninjau dan menangani permohonan surat.
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
        Route::patch('permohonan/{letter}/kirim', 'approve')->name('approve');
        Route::patch('permohonan/{letter}/tolak', 'reject')->name('reject');
        Route::delete('bulk-delete', 'bulkDelete')->name('bulkDelete');
    });

    // Grup route untuk mengelola kegiatan (event).
    Route::group([
        'controller' => EventController::class,
        'as' => 'kegiatan.',
        'prefix' => 'kegiatan',
    ], function () {
        Route::get('/', 'index')->name('index');
        Route::get('/diikuti', 'indexJoined')->name('indexJoined');
        Route::get('/arsip', 'indexArchive')->name('indexArchive');

        Route::get('tambah', 'create')->name('create');
        Route::post('/', 'store')->name('store');

        Route::get('{event}', 'show')->name('show');
        Route::get('{event}/peserta', 'showParticipant')->name('showParticipant');
        Route::get('{event}/ekspor-peserta', 'exportParticipant')->name('exportParticipant');

        Route::get('{event}/edit', 'edit')->name('edit');
        Route::patch('{event}', 'update')->name('update');
        Route::delete('{event}', 'destroy')->name('destroy');
        Route::patch('{event}/finish', 'finish')->name('finish');

        Route::post('{event}/ikuti', 'join')->name('join');
    });

    // Grup route untuk mengelola sertifikat kegiatan.
    Route::group([
        'controller' => EventCertificateController::class,
        'as' => 'sertifikat.',
        'prefix' => 'kegiatan',
    ], function () {
        Route::get('{event}/sertifikat', 'index')->name('index');
        Route::get('{event}/sertifikat/tambah', 'create')->name('create');
        Route::post('{event}/sertifikat', 'store')->name('store');
        Route::get('{event}/sertifikat/{certificate}/edit', 'edit')->name('edit');
        Route::patch('{event}/sertifikat/{certificate}', 'update')->name('update');
        Route::delete('{event}/sertifikat/{certificate}', 'destroy')->name('destroy');
        Route::get('{event}/download-sertifikat', 'downloadForRelawan')->name('downloadForRelawan');
    });

    // Grup route untuk mengelola materi (material).
    Route::group([
        'controller' => MaterialController::class,
        'as' => 'materi.',
        'prefix' => 'materi'
    ], function () {
        Route::get('/', 'index')->name('index');
        Route::get('tambah', 'create')->name('create');
        Route::post('/', 'store')->name('store');
        Route::get('{material}', 'show')->name('show');
        Route::get('{material}/edit', 'edit')->name('edit');
        Route::patch('{material}', 'update')->name('update');
        Route::delete('{material}', 'destroy')->name('destroy');
    });
});
