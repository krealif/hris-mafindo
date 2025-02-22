@extends('layouts.auth', [
    'title' => 'Masuk',
])

@section('content')
  <div class="card card-mafindo">
    <div class="card-header">
      <h1 class="card-title">Masuk ke Akun Anda</h1>
    </div>
    <div class="card-body">
      <form action="{{ route('login.store') }}" method="POST" autocomplete="off">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label required">Email</label>
          <x-form.input name="email" type="email" value="{{ old('email') }}" required />
        </div>
        <div class="mb-3">
          <label for="password" class="form-label required">
            Password
            <span class="form-label-description">
              <a href="{{ route('password.request') }}">Lupa password?</a>
            </span>
          </label>
          <x-form.input name="password" type="password" :showError=false required />
        </div>
        <div class="form-footer">
          <button type="submit" class="btn btn-primary w-100">Masuk</button>
          <a href="{{ route('register') }}" class="btn w-100 mt-2">Belum punya akun?&nbsp;<span class="text-primary">Registrasi</span></a>
        </div>
      </form>
    </div>
  </div>
  <div class="card card-body mt-2">
    <p>Jika mengalami kendala dalam mengakses sistem, silakan hubungi kami di <a href='mailto:organisasi@mafindo.or.id'>organisasi@mafindo.or.id</a></p>
  </div>
@endsection
