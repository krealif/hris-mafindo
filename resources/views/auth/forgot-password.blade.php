@extends('layouts.auth', [
  'title' => 'Password'
])

@section('content')
<div class="card">
  <div class="card-header d-block">
    <h2 class="h2 mb-2">Lupa Password</h2>
    <p class="text-muted mb-0">Tuliskan email Anda di bawah ini dan kami akan mengirimkan email yang berisi tautan untuk mengatur ulang password Anda.</p>
  </div>
  <div class="card-body">
    @if (session('status'))
    <div class="alert alert-success" role="alert">
      <h4 class="alert-title">Sukses!</h4>
      <div class="text-secondary">Tautan reset password telah dikirim ke email Anda</div>
    </div>
    @endif

    <form action="{{ route('password.email') }}" method="POST" autocomplete="off">
      @csrf
      <div class="mb-3">
        <label for="email" class="form-label required">Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="contoh@gmail.com" autocomplete="off" required>
        @error('email')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div class="form-footer">
        <button type="submit" class="btn btn-main w-100">Kirim Tautan Reset</button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">Kembali ke halaman masuk</a>
      </div>
    </form>
  </div>
</div>
@endsection
