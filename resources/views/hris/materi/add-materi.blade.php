@extends('layouts.dashboard', [
  'title' => 'Tambah Materi'
])

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <a class="pe-auto text-decoration-none cursor-pointer" onclick="history.back()">
                    <div class="d-flex align-items-center">
                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
                        <p class="mb-0 ms-2">Kembali</p>
                    </div>
                </a>
            </div>
        </div>
    </div>
    <div class="page-body">
        <div class="container-xl">
            <div class="col-12">
                <form class="card">
                    <div class="card-header justify-content-center">
                        <h2 class="card-title">Form Materi</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label class="form-label">Judul Materi</label>
                            <input type="text" class="form-control" placeholder="Tuliskan judul materi">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tautan Materi</label>
                            <input type="text" class="form-control" placeholder="Masukkan tautan materi">
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn btn-main">Simpan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
