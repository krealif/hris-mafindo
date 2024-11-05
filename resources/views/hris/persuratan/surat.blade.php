@extends('layouts.dashboard', [
  'title' => 'Surat'
])

@section('content')
<div class="page-wrapper">
  <!-- Judul Halaman -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">
              Pengajuan Surat
            </h2>
          </div>
          <div class="col-auto ms-auto d-print-none">
            <div class="d-flex">
                <a href="./jenis-surat" class="btn btn-main">
                <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M12 5l0 14" /><path d="M5 12l14 0" /></svg>
                Buat Surat
                </a>
            </div>
          </div>
        </div>
      </div>
    </div>
    <!-- End Judul Halaman -->

    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="col-12">
          <div class="card">
            <div class="card-body border-bottom py-3">
              <div class="d-flex">
                <div class="text-secondary">
                  Tampilkan
                  <div class="mx-2 d-inline-block">
                    <input type="text" class="form-control form-control-sm" value="8" size="3" aria-label="Invoices count">
                  </div>
                  baris
                </div>
                <div class="ms-auto text-secondary">
                  Cari:
                  <div class="ms-2 d-inline-block">
                    <input type="text" class="form-control form-control-sm" aria-label="Search invoice">
                  </div>
                </div>
              </div>
            </div>
            <div class="table-responsive">
              <table class="table table-vcenter card-table table-striped">
                <thead>
                  <tr>
                    <th>Judul Surat</th>
                    <th>Nama</th>
                    <th>Tanggal</th>
                    <th>Status</th>
                    <th class="w-1">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Maryjo Lebarree</td>
                    <td>12 Oktober 2024</td>
                    <td><span class="badge bg-success me-1"></span>Disetujui</td>
                    <td>
                      <div class="flex-nowrap">
                        <div class="dropdown">
                          <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                            Aksi
                          </button>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                              Tampilkan Surat
                            </a>
                            <a class="dropdown-item" href="#">
                              Setuju
                            </a>
                            <a class="dropdown-item" class="btn" data-bs-toggle="modal" data-bs-target="#modal-reject">
                              Tolak
                            </a>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Maryjo Lebarree</td>
                    <td>12 Oktober 2024</td>
                    <td><span class="badge bg-success me-1"></span>Disetujui</td>
                    <td>
                      <div class="flex-nowrap">
                        <div class="dropdown">
                          <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                            Aksi
                          </button>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                              Tampilkan Surat
                            </a>
                            <a class="dropdown-item" href="#">
                              Setuju
                            </a>
                            <a class="dropdown-item" class="btn" data-bs-toggle="modal" data-bs-target="#modal-reject">
                              Tolak
                            </a>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Maryjo Lebarree</td>
                    <td>12 Oktober 2024</td>
                    <td><span class="badge bg-danger me-1"></span>Ditolak</td>
                    <td>
                      <div class="flex-nowrap">
                        <div class="dropdown">
                          <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                            Aksi
                          </button>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                              Tampilkan Surat
                            </a>
                            <a class="dropdown-item" href="#">
                              Setuju
                            </a>
                            <a class="dropdown-item" class="btn" data-bs-toggle="modal" data-bs-target="#modal-reject">
                              Tolak
                            </a>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Maryjo Lebarree</td>
                    <td>12 Oktober 2024</td>
                    <td><span class="badge bg-warning me-1"></span>Diproses</td>
                    <td>
                      <div class="flex-nowrap">
                        <div class="dropdown">
                          <button class="btn dropdown-toggle align-text-top" data-bs-toggle="dropdown">
                            Aksi
                          </button>
                          <div class="dropdown-menu dropdown-menu-end">
                            <a class="dropdown-item" href="#">
                              Tampilkan Surat
                            </a>
                            <a class="dropdown-item" href="#">
                              Setuju
                            </a>
                            <a class="dropdown-item" class="btn" data-bs-toggle="modal" data-bs-target="#modal-reject">
                              Tolak
                            </a>
                          </div>
                        </div>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
            <div class="card-footer d-flex align-items-center">
              <p class="m-0 text-secondary">Menampilkan <span>1</span> hingga <span>8</span> dari <span>16</span> baris</p>
              <ul class="pagination m-0 ms-auto">
                <li class="page-item disabled">
                  <a class="page-link" href="#" tabindex="-1" aria-disabled="true">
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M15 6l-6 6l6 6" /></svg>
                    sebelumnya
                  </a>
                </li>
                <li class="page-item"><a class="page-link" href="#">1</a></li>
                <li class="page-item"><a class="page-link" href="#">2</a></li>
                <li class="page-item"><a class="page-link" href="#">3</a></li>
                <li class="page-item"><a class="page-link" href="#">4</a></li>
                <li class="page-item"><a class="page-link" href="#">5</a></li>
                <li class="page-item">
                  <a class="page-link" href="#">
                    selanjutnya
                    <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 6l6 6l-6 6" /></svg>
                  </a>
                </li>
              </ul>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
<div class="modal modal-blur fade" id="modal-reject" tabindex="-1" role="dialog" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Alasan Penolakan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <div class="row mb-3 align-items-end">
          <div class="col">
            <label class="form-label">Email</label>
            <input type="text" class="form-control" />
          </div>
        </div>
        <div>
          <label class="form-label">Alasan</label>
          <textarea class="form-control"></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn me-auto" data-bs-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-main" data-bs-dismiss="modal">Kirim</button>
      </div>
    </div>
  </div>
</div>
@endsection
