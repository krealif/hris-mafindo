@extends('layouts.auth', [
  'title' => 'Daftar'
])

@section('content')
<div class="card">
  <div class="card-header">
    <h2 class="h2 mb-0">Registrasi Akun Baru</h2>
  </div>
  <div class="card-body">
    <form action="{{ route('register.store') }}" method="POST" autocomplete="off">
      @csrf
      @honeypot
      <div class="mb-3">
        <label for="email" class="form-label required">Email</label>
        <x-form.input name="email" type="email" placeholder="contoh@gmail.com" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label required">Password</label>
        <x-form.input name="password" type="password" placeholder="Password Anda" required />
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
@endsection
