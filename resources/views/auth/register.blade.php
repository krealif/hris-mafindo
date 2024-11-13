@extends('layouts.auth', [
  'title' => 'Daftar'
])

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
        <x-form.input name="name" type="text" placeholder="Budi" value="{{ old('name') }}" required />
      </div>
      <div class="mb-3">
        <label for="member-number" class="form-label">Nomor Induk</label>
        <x-form.input name="member_number" type="text" placeholder="XXXXX" value="{{ old('member_number') }}" />
      </div>
      <div class="mb-3">
        <label for="branch" class="form-label">Wilayah</label>
        <x-form.tom-select id="branch" name="branch_id" :options=$branches>
          <x-slot:placeholder>
            <option value="">Pilih wilayah</option>
          </x-slot>
        </x-form.tom-select>
      </div>
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
        <a href="{{ route('login') }}" class="btn btn-outline-secondary w-100 mt-2">Sudah punya akun? Masuk</a>
      </div>
    </form>
  </div>
</div>
@endsection
