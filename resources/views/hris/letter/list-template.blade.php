@extends('layouts.dashboard', [
  'title' => 'Jenis Surat'
])

@section('content')
<div class="page-wrapper">
  <!-- Judul Halaman -->
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="mb-1">
            <x-breadcrumb>
              <x-breadcrumb-item label="Persuratan" route="letter.index" />
            </x-breadcrumb>
          </div>
          <h1 class="page-title">
            Pilih Template Surat
          </h1>
        </div>
      </div>
    </div>
  </div>
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
                </tr>
              </thead>
              <tbody>
                @foreach($letters as $letter)
                <tr>
                  <td>
                    <a href="{{ route('letter.create', $letter->view) }}">{{ $letter->name }}</a>
                  </td>
                </tr>
                @endforeach
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
        <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Kirim</button>
      </div>
    </div>
  </div>
</div>
@endsection
