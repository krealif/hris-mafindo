@extends('layouts.auth', [
    'title' => 'Password',
])

@section('content')
  @if (session('status'))
    <div class="alert alert-success" role="alert">
      <h4 class="alert-title h3">Sukses</h4>
      <div class="text-secondary">Kami telah mengirimkan email berisi instruksi untuk mereset password Anda. Jika email tidak segera muncul di kotak masuk Anda, harap periksa folder spam
        atau tunggu beberapa saat.</div>
    </div>
  @endif
  <div class="card card-mafindo">
    <div class="card-header d-block">
      <h1 class="card-title mb-2">Lupa Password</h1>
      <p class="text-secondary mb-0">Tuliskan email Anda di bawah ini dan kami akan mengirimkan email yang berisi tautan untuk mengatur ulang password Anda.</p>
    </div>
    <div class="card-body">
      <form action="{{ route('password.email') }}" method="POST" autocomplete="off">
        @csrf
        <div class="mb-3">
          <label for="email" class="form-label required">Email</label>
          <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" autocomplete="off" required>
          @error('email')
            <div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>
          @enderror
        </div>
        <div class="form-footer">
          <button type="submit" class="btn btn-primary w-100">Kirim Tautan Reset</button>
          <a href="{{ route('login') }}" class="btn w-100 mt-2">Kembali ke Halaman Masuk</a>
        </div>
      </form>
    </div>
    <input type="text" x-data @keyup.shift.enter="alert('Hello world!')" hidden>
  </div>
@endsection
