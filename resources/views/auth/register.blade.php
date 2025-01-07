@extends('layouts.auth', [
    'title' => 'Daftar',
])

@section('content')
  <div class="card card-mafindo">
    <div class="card-header">
      <h1 class="h2 mb-0">Registrasi Akun Baru</h1>
    </div>
    <div class="card-body">
      <form action="{{ route('register.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="mb-3">
          <label for="nama" class="form-label required">Nama Lengkap</label>
          <x-form.input name="nama" type="text" placeholder="Tuliskan nama Anda sesuai KTP" value="{{ old('nama') }}" required />
        </div>
        <div class="mb-3">
          <label for="email" class="form-label required">Email</label>
          <x-form.input name="email" type="email" placeholder="Tuliskan email Anda yang aktif" value="{{ old('email') }}" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label required">Password</label>
          <x-form.input name="password" type="password" placeholder="Tuliskan password Anda" required />
        </div>
        <div class="mb-3">
          <label for="password-confirm" class="form-label required">Konfirmasi Password</label>
          <x-form.input name="password_confirmation" type="password" placeholder="Tuliskan kembali password Anda" required />
        </div>
        <div class="form-footer">
          <button type="submit" class="btn btn-primary w-100">Daftar</button>
          <a href="{{ route('login') }}" class="btn w-100 mt-2">Sudah punya akun?&nbsp;<span class="text-primary">Masuk</span></a>
        </div>
      </form>
    </div>
  </div>
  <div class="card card-body mt-2">
    <p>Jika mengalami kendala dalam mengakses sistem, silakan hubungi kami di <a href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a></p>
  </div>
@endsection
