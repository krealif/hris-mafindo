@extends('layouts-admin.app')
@section('pageTitle', isset($pageTitle) ? $pageTitle : 'Detail Kegiatan' )

@section('content')
<div class="page-wrapper">
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
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <div class="col">
                    <h1 class="page-title">
                        Judul Kegiatan
                    </h2>
                </div>
            </div>
        </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
        <div class="container-xl">
            <div class="col-12">
                <div class="card">
                    <div class="row row-0">
                        <div class="col-3">
                            <!-- Photo -->
                            <img src="https://preview.tabler.io/static/photos/home-office-desk-with-macbook-iphone-calendar-watch-and-organizer.jpg" class="w-100 h-100 object-cover card-img-start" alt="Beautiful blonde woman relaxing with a can of coke on a tree stump by the beach" />
                        </div>
                        <div class="col">
                            <div class="card-body">
                            <h3 class="card-title">Deskripsi Kegiatan</h3>
                            <p class="text-secondary">Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aperiam deleniti fugit
                                incidunt, iste, itaque minima
                                neque pariatur perferendis sed suscipit velit vitae voluptatem.</p>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12 mt-3">
                    <div class="d-flex flex-column gap-2">
                        <a href="#" data-bs-toggle="modal" data-bs-target="#modal-tautan" class="btn btn-main ms-auto">Unggah Rekaman</a>
                        <a href="#" class="btn btn-main ms-auto">Tampilkan Prsesnsi</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-tautan" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Unggah Rekaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="col-12">
                    <form class="card">
                        <div class="card-body">
                            <div class="mb-3">
                                <label class="form-label">Tautan Rekaman</label>
                                <input type="text" class="form-control" placeholder="Masukan tautan rekaman">
                            </div>
                        </div>
                        <div class="card-footer text-center">
                            <a href="#" class="btn btn-main">Simpan</a>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection
