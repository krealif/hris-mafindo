@extends('layouts.dashboard', [
  'title' => 'Detail Surat'
])

@section('content')
<div class="page-wrapper">
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
            Review Surat
          </h1>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="page-body">
  <div class="container-xl">
    <div class="row">
      <div class="col-12">
        @if(flash()->message)
          <x-alert class="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
      </div>
      <div class="col-12 col-md-6">
        <div class="card">
          <div class="card-header">
            <h2 class="card-title d-inline-block badge bg-dark text-white">{{ $letter->letterTemplate->name }}</h2>
          </div>
          <div class="card-body">
            <h4 class="fs-3">Info Relawan</h4>
            <div class="datagrid">
              <div class="datagrid-item">
                <div class="datagrid-title fs-4">Nama</div>
                <div class="datagrid-content">{{ $letter->user->name }}</div>
              </div>
              <div class="datagrid-item">
                <div class="datagrid-title fs-4">Email</div>
                <div class="datagrid-content">{{ $letter->user->email }}</div>
              </div>
              <div class="datagrid-item">
                <div class="datagrid-title fs-4">Wilayah</div>
                <div class="datagrid-content">{{ $letter->user->branch?->name }}</div>
              </div>
            </div>
          </div>
          <div class="card-body">
            <h4 class="fs-3">Isi Surat</h4>
            <div class="datagrid">
              @foreach($letter->contents as $label => $value)
              <div class="datagrid-item">
                <div class="datagrid-title fs-4">{{ $label }}</div>
                <div class="datagrid-content">{{ $value }}</div>
              </div>
              @endforeach
            </div>
          </div>
        </div>
      </div>
      <div class="col-12 col-md-6">
        @if($errors->any())
          <!-- Validation error -->
          <x-alert class="alert-danger">
            Error! Terjadi kesalahan saat mengirimkan form. Tolong periksa kembali data yang Anda masukkan.
          </x-alert>
        @endif
        <div class="card">
          <div class="card-header">
            <ul class="nav nav-pills card-header-pills gap-2" role="tablist">
              @if($letter->file)
              <li class="nav-item" role="presentation">
                <a href="#tab-file" class="nav-link fs-3 active" data-bs-toggle="tab" aria-selected="true" role="tab">
                  <svg class="icon text-green me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M10 9H8"/><path d="M16 13H8"/><path d="M16 17H8"/></svg>
                  File
                </a>
              </li>
              @endif
              <li class="nav-item" role="presentation">
                <a href="#tab-upload" class="nav-link fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                  <svg class="icon text-blue me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"/><polyline points="17 8 12 3 7 8"/><line x1="12" x2="12" y1="3" y2="15"/></svg>
                  Upload
                </a>
              </li>
              <li class="nav-item" role="presentation">
                <a href="#tab-upload" class="nav-link fs-3" href="#tab-reject" data-bs-toggle="tab" aria-selected="true" role="tab">
                  <svg class="icon text-red me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 6 6 18"/><path d="m6 6 12 12"/></svg>
                  Tolak
                </a>
              </li>
            </ul>
          </div>
          <div class="tab-content">
            @if($letter->file)
            <div id="tab-file" class="tab-pane active show">
              <form action="POST" class="card-body">
                <div class="mb-4">
                  <a href="{{ route('letter.download', $letter->id) }}" class="d-flex flex-row fs-3" target="_blank">
                    <svg class="icon me-2" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M15 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V7Z"/><path d="M14 2v4a2 2 0 0 0 2 2h4"/><path d="M12 18v-6"/><path d="m9 15 3 3 3-3"/></svg>
                    {{ explode('/', $letter->file)[1] }}
                  </a>
                  <div class="datagrid mt-3">
                    <div class="datagrid-item">
                      <div class="datagrid-title fs-4">Diupload Oleh</div>
                      <div class="datagrid-content">{{ $letter->admin }}</div>
                    </div>
                    <div class="datagrid-item">
                      <div class="datagrid-title fs-4">Tanggal & Waktu</div>
                      <div class="datagrid-content">{{ $letter->updated_at }}</div>
                    </div>
                  </div>
                </div>
                <div class="btn-list">
                  <button class="btn btn-primary" type="submit">Kirim</button>
                  <a href="{{ route('letter.download', $letter->id) }}" class="btn" target="_blank">Download</a>
                </div>
              </form>
            </div>
            @endif
            <div id="tab-upload" class="tab-pane">
              <form class="card-body" action="{{ route('letter.upload', $letter->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                  <label for="admin" class="form-label required">Admin</label>
                  <x-form.input name="admin" type="text" placeholder="Tuliskan nama admin" required />
                </div>
                <div class="mb-4">
                  <label for="letter" class="form-label required">Upload Surat</label>
                  <x-form.input name="letter" type="file" required />
                </div>
                <button class="btn btn-primary" type="submit">Simpan</button>
              </form>
            </div>
            <div id="tab-reject" class="tab-pane">
              <form method="POST" class="card-body">
                <div class="mb-3">
                  <label for="admin" class="form-label required">Admin</label>
                  <x-form.input name="admin" type="text" placeholder="Tuliskan nama admin" :showError=false required />
                </div>
                <div class="mb-4">
                  <label for="message" class="form-label required">Alasan</label>
                  <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan penolakan" :showError=false required />
                </div>
                <button class="btn btn-primary" type="submit">Kirim</button>
              </form>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
