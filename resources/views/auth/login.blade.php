@extends('layouts.auth', [
  'title' => 'Masuk'
])

@section('content')
<div class="card">
  <div class="card-header">
    <h2 class="h2 mb-0">Masuk ke Akun Anda</h2>
  </div>
  <div class="card-body">
    <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label required">Email</label>
        <x-form.input name="email" type="email" placeholder="contoh@gmail.com" required />
      </div>
      <div class="mb-3">
        <label for="password" class="form-label required">
          Password
          <span class="form-label-description">
            <a href="{{ route('password.request') }}">Lupa password?</a>
          </span>
        </label>
        <x-form.input name="password" type="password" placeholder="Password Anda" withError="false" required />
      </div>
      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Masuk</button>
        <a href="{{ route('register') }}" class="btn btn-outline-secondary w-100 mt-2">Belum punya akun? Daftar sekarang</a>
      </div>
    </form>
  </div>
</div>
@endsection
