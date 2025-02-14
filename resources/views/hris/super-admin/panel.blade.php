@extends('layouts.dashboard', [
    'title' => 'Panel',
])

@section('body')
  <div class="d-flex flex-column">
    <header>
      @include('partials.navbar-top')
    </header>
    <div class="page-wrapper">
      <!-- Page header -->
      <div class="page-header d-print-none">
        <div class="container-xl">
          <div class="title-wrapper">
            <h1 class="page-title">
              Panel
            </h1>
          </div>
        </div>
      </div>
      <!-- Page body -->
      <div class="page-body">
        <div class="container-xl">
          @if (flash()->message)
            <x-alert type="{{ flash()->class }}">
              {{ flash()->message }}
            </x-alert>
          @endif
          @if ($errors->any())
            <x-alert class="alert-danger">
              <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
              <ul class="mt-2 mb-0" style="margin-left: -1rem">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </x-alert>
          @endif
          <div class="row g-3">
            <div class="col-12 col-md-6 col-lg-4">
              <form method="POST" action="{{ route('superadmin.createAdmin') }}" class="card card-mafindo">
                @csrf
                <div class="card-header d-block">
                  <h2 class="card-title mb-2">Tambah Admin</h2>
                  <p class="text-muted mb-0">Tambahkan Admin seperlunya saja jika dibutuhkan. Hal tersebut karena penghapusan Admin hanya bisa dilakukan secara manual di luar sistem.
                    Penghapusan Admin yang sembarangan ditakutkan dapat mengganggu fitur "Buat Surat untuk Pengurus/Relawan".</p>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="nama" class="form-label required">Nama</label>
                    <x-form.input name="nama" type="text" required />
                  </div>
                  <div class="mb-3">
                    <label for="email" class="form-label required">Email</label>
                    <x-form.input name="email" type="email" required />
                  </div>
                  <div class="mb-3">
                    <label for="password" class="form-label required">Password</label>
                    <x-form.input name="password" type="password" required />
                  </div>
                </div>
                <div class="card-body btn-list">
                  <button type="submit" class="btn btn-primary">Tambah</button>
                </div>
              </form>
            </div>
            <div class="col-12 col-md-6 col-lg-4">
              <form method="POST" action="{{ route('superadmin.deleteUser') }}" class="card card-mafindo">
                @csrf
                <div class="card-header d-block">
                  <h2 class="card-title mb-2">Hapus Pengguna</h2>
                  <p class="text-muted mb-0">Pengguna yang dapat dihapus adalah Pengurus dan Relawan yang sudah teregistrasi. Hati-hati dan cermati saat
                    menghapus pengguna karena hal ini juga akan menghapus seluruh data yang berkaitan dengan pengguna tersebut.</p>
                </div>
                <div class="card-body">
                  <div class="mb-3">
                    <label for="user_id" class="form-label required">Nama</label>
                    <x-form.user-select name="user_id" required />
                  </div>
                  <div class="mb-2">
                    <label for="confirmation" class="form-label required mb-1">Konfirmasi</label>
                    <small class="d-block form-hint mb-2">Tulis ulang nama pengguna yang akan dihapus</small>
                    <x-form.input name="confirmation" type="text" required />
                  </div>
                </div>
                <div class="card-body btn-list">
                  <button type="submit" class="btn btn-danger">Hapus</button>
                </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  </div>
@endsection
