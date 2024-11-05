<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('admins-page.dashboard');
});

Route::get('/admin', function () {
    return view('admins-page.dashboard');
});

#Route Relawan
Route::get('/daftar-akun', function () {
    return view('admins-page.relawan.daftar-akun');
});
Route::get('/data-relawan', function () {
    return view('admins-page.relawan.data-relawan');
});

#Route Surat
Route::get('/admin-surat', function () {
    return view('admins-page.persuratan.surat');
});
Route::get('/jenis-surat', function () {
    return view('admins-page.persuratan.jenis-surat');
});

#Route Kegiatan
Route::get('/admin-kegiatan', function () {
    return view('admins-page.kegiatan.kegiatan');
});
Route::get('/admin-kegiatan-detail', function () {
    return view('admins-page.kegiatan.detail-kegiatan');
});
Route::get('/admin-kegiatan-detail-lampau', function () {
    return view('admins-page.kegiatan.detail-kegiatan-lampau');
});
Route::get('/add-kegiatan', function () {
    return view('admins-page.kegiatan.add-kegiatan');
});

#Route Kegiatan
Route::get('/admin-materi', function () {
    return view('admins-page.materi.materi');
});
Route::get('/add-materi', function () {
    return view('admins-page.materi.add-materi');
});


