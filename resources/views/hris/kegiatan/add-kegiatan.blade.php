@extends('layouts.dashboard', [
  'title' => 'Tambah Kegiatan'
])

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
                <a class="pe-auto text-decoration-none cursor-pointer" onclick="history.back()">
                    <div class="d-flex align-items-center">
                        <svg xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-arrow-left"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M5 12l14 0" /><path d="M5 12l6 6" /><path d="M5 12l6 -6" /></svg>
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
                        <h2 class="card-title">Form Kegiatan</h2>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-auto">
                                    <span class="avatar avatar-xl">
                                        <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-photo"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8.813 11.612c.457 -.38 .918 -.38 1.386 .011l.108 .098l4.986 4.986l.094 .083a1 1 0 0 0 1.403 -1.403l-.083 -.094l-1.292 -1.293l.292 -.293l.106 -.095c.457 -.38 .918 -.38 1.386 .011l.108 .098l4.674 4.675a4 4 0 0 1 -3.775 3.599l-.206 .005h-12a4 4 0 0 1 -3.98 -3.603l6.687 -6.69l.106 -.095zm9.187 -9.612a4 4 0 0 1 3.995 3.8l.005 .2v9.585l-3.293 -3.292l-.15 -.137c-1.256 -1.095 -2.85 -1.097 -4.096 -.017l-.154 .14l-.307 .306l-2.293 -2.292l-.15 -.137c-1.256 -1.095 -2.85 -1.097 -4.096 -.017l-.154 .14l-5.307 5.306v-9.585a4 4 0 0 1 3.8 -3.995l.2 -.005h12zm-2.99 5l-.127 .007a1 1 0 0 0 0 1.986l.117 .007l.127 -.007a1 1 0 0 0 0 -1.986l-.117 -.007z" /></svg>
                                    </span>
                                </div>
                                <div class="col">
                                    <div class="mb-3">
                                        <label class="form-label">Tambahkan Foto</label>
                                        <input type="file" class="form-control" />
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" placeholder="Tuliskan nama kegiatan">
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Kegiatan</label>
                            <textarea class="form-control" rows="5" placeholder="Tuliskan deskripsi kegiatan"></textarea>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Waktu Kegiatan</label>
                            <input class="form-control" type="date">
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label">Waktu Mulai</label>
                                    <input class="form-control" type="time">
                                </div>
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label">Waktu Selesai</label>
                                    <input class="form-control" type="time">
                                </div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="row">
                                <div class="col-sm-6 col-md-6">
                                    <div class="form-label">Bentuk Kegiatan</div>
                                        <select class="form-select">
                                            <option selected>Pilih bentuk kegiatan</option>
                                            <option value="1">Terbuka</option>
                                            <option value="2">Terbatas</option>
                                        </select>
                                    </div>
                                <div class="col-sm-6 col-md-6">
                                    <label class="form-label">Jumlah Peserta</label>
                                    <input type="text" class="form-control" placeholder="Tuliskan jumlah maksimal peserta kegiatan">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-footer text-center">
                        <a href="#" class="btn btn-primary">Simpan</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
