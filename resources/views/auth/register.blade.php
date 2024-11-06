@extends('layouts.auth', [
  'title' => 'Masuk'
])

@push('style')
  <style>
    body {
      background-color: var(--main-purple);
    }
  </style>
@endpush

@section('content')
<div class="page page-center">
  <div class="container container-tight py-4">
    <div class="text-center mb-4">
      <a href=".">
        <img src="{{ asset('static/mafindo-logo.png') }}" height="56" alt="Mafindo Logo">
      </a>
    </div>
    <div class="card">
      <div class="card-header">
        <h2 class="h2 text-center mb-0">Pendaftaran Akun Baru</h2>
      </div>
      <div class="card-body">
        <form action="./" method="get" autocomplete="off" novalidate>
          <div class="mb-3">
            <label class="form-label">Nama</label>
            <input type="text" class="form-control" autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">Nomor Induk</label>
            <input type="number" class="form-control" autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">Alamat Email</label>
            <input type="email" class="form-control" autocomplete="off">
          </div>
          <div class="mb-3">
            <label class="form-label">
              Password
            </label>
            <div class="input-group input-group-flat">
              <input type="password" class="form-control"  placeholder="Your password"  autocomplete="off">
              <span class="input-group-text">
                <a href="#" class="link-secondary" title="Show password" data-bs-toggle="tooltip"><!-- Download SVG icon from http://tabler-icons.io/i/eye -->
                  <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10 12a2 2 0 1 0 4 0a2 2 0 0 0 -4 0" /><path d="M21 12c-2.4 4 -5.4 6 -9 6c-3.6 0 -6.6 -2 -9 -6c2.4 -4 5.4 -6 9 -6c3.6 0 6.6 2 9 6" /></svg>
                </a>
              </span>
            </div>
          </div>
          <div class="form-footer">
            <button type="submit" class="btn btn-main w-100">Daftar</button>
            <a class="btn btn-outline-secondary w-100 mt-2">Sudah punya akun? Masuk</a>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection
