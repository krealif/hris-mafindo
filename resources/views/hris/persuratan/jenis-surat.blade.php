@extends('layouts.dashboard', [
  'title' => 'Jenis Surat'
])

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
  <!-- Judul Halaman -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">
              Buat Surat
            </h2>
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
            <div class="table-responsive">
              <table class="table table-vcenter card-table table-striped">
                <thead>
                  <tr>
                    <th>Jenis Surat</th>
                    <th>Deskripsi Surat</th>
                    <th class="w-1">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit rem quod molestiae tenetur voluptate, error fuga et vero accusamus sint hic! Laudantium iure debitis, nesciunt eius error nulla asperiores vero.</td>
                    <td>
                      <div class="flex-nowrap">
                        <a href="#" class="btn">
                            Buat
                        </a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit rem quod molestiae tenetur voluptate, error fuga et vero accusamus sint hic! Laudantium iure debitis, nesciunt eius error nulla asperiores vero.</td>
                    <td>
                      <div class="flex-nowrap">
                        <a href="#" class="btn">
                            Buat
                        </a>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td>Surat Cuti</td>
                    <td>Lorem ipsum dolor sit amet consectetur adipisicing elit. Reprehenderit rem quod molestiae tenetur voluptate, error fuga et vero accusamus sint hic! Laudantium iure debitis, nesciunt eius error nulla asperiores vero.</td>
                    <td>
                      <div class="flex-nowrap">
                        <a href="#" class="btn">
                            Buat
                        </a>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
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
