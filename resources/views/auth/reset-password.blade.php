@extends('layouts.auth', [
  'title' => 'Password'
])

@section('content')
@if (session('status'))
<div class="alert alert-success" role="alert">
  <h4 class="alert-title h3">Berhasil!</h4>
  <div class="text-muted">Password Anda telah berhasil direset. Silakan kembali ke halaman masuk.</div>
</div>
@endif
<div class="card">
  <div class="card-header d-block">
    <h2 class="h2 mb-0">Reset Passwoord</h2>
    <p class="text-muted mb-0">Tuliskan password baru Anda.</p>
  </div>
  <div class="card-body">
    <form action="{{ route('password.update') }}" method="POST" autocomplete="off">
      @csrf
      <input type="hidden" name="token" value="{{ $request->route('token') }}">
      <div class="mb-3">
        <label for="email" class="form-label required">Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" value="{{ $request->email }}" readonly>
        @error('email')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div class="mb-3">
        <label for="password" class="form-label required">Password</label>
        <input id="password" name="password" type="password" class="form-control @error('password') is-invalid @enderror" placeholder="Password Anda" autocomplete="off" required>
        @error('password')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div>
        <label for="password-confirm" class="form-label required">Konfirmasi Password</label>
        <input id="password-confirm" name="password_confirmation" type="password" class="form-control @error('password_confirmation') is-invalid @enderror" placeholder="Tuliskan kembali password Anda" required>
        @error('password_confirmation')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div class="form-footer">
        <button type="submit" class="btn btn-primary w-100">Kirim Tautan Reset</button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">Kembali ke Halaman Masuk</a>
      </div>
    </form>
  </div>
</div>
@endsection
