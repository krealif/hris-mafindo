<?php

use Illuminate\Support\Facades\Route;

Route::middleware('auth')->group(function () {
    Route::get('/', function () {
        return view('hris.dashboard');
    });
});

// Route::get('/admin', function () {
//     return view('hris.dashboard');
// });

// #Route Relawan
// Route::get('/daftar-akun', function () {
//     return view('hris.relawan.daftar-akun');
// });
// Route::get('/data-relawan', function () {
//     return view('hris.relawan.data-relawan');
// });

// #Route Surat
// Route::get('/admin-surat', function () {
//     return view('hris.persuratan.surat');
// });
// Route::get('/jenis-surat', function () {
//     return view('hris.persuratan.jenis-surat');
// });

// #Route Kegiatan
// Route::get('/admin-kegiatan', function () {
//     return view('hris.kegiatan.kegiatan');
// });
// Route::get('/admin-kegiatan-detail', function () {
//     return view('hris.kegiatan.detail-kegiatan');
// });
// Route::get('/admin-kegiatan-detail-lampau', function () {
//     return view('hris.kegiatan.detail-kegiatan-lampau');
// });
// Route::get('/add-kegiatan', function () {
//     return view('hris.kegiatan.add-kegiatan');
// });

// #Route Kegiatan
// Route::get('/admin-materi', function () {
//     return view('hris.materi.materi');
// });
// Route::get('/add-materi', function () {
//     return view('hris.materi.add-materi');
// });
