@extends('layouts.auth', [
  'title' => 'Daftar'
])

@push('style')
<link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
@endpush

@push('script')
<script src="{{ asset('static/vendor/tom-select.complete.min.js') }}"></script>
<script>
  new TomSelect('#branch');
</script>
@endpush

@section('content')
<div class="card">
  <div class="card-header">
    <h2 class="h2 mb-0">Pendaftaran Akun Baru</h2>
  </div>
  <div class="card-body">
    <form action="{{ route('register.store') }}" method="POST" autocomplete="off">
      @csrf
      @honeypot
      <div class="mb-3">
        <label for="name" class="form-label required">Nama</label>
        <input id="name" name="name" type="text" class="form-control @error('name') is-invalid @enderror" placeholder="Budi" autocomplete="off" value="{{ old('name') }}" required>
        @error('name')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div class="mb-3">
        <label for="member-number" class="form-label">Nomor Induk</label>
        <input id="member-number" name="member_number" type="text" class="form-control @error('member_number') is-invalid @enderror" placeholder="XXXXX" autocomplete="off">
        @error('member_number')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div class="mb-3">
        <label for="branch" class="form-label">Wilayah</label>
        <select id="branch" name="branch_id" class="form-select @error('branch_id') is-invalid @enderror" placeholder="Pilih wilayah" autocomplete="off">
          <option value="">Pilih wilayah</option>
          @foreach ($branches as $branch)
          <option value="{{ $branch->id }}">{{ $branch->name }}</option>
          @endforeach
        </select>
        @error('branch_id')<div class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></div>@enderror
      </div>
      <div class="mb-3">
        <label for="email" class="form-label required">Email</label>
        <input id="email" name="email" type="email" class="form-control @error('email') is-invalid @enderror" placeholder="contoh@gmail.com" autocomplete="off" value="{{ old('email') }}" required>
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
        <button type="submit" class="btn btn-primary w-100">Daftar</button>
        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">Sudah punya akun? Masuk</a>
      </div>
    </form>
  </div>
</div>
@endsection
