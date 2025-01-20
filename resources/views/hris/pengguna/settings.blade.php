@extends('layouts.dashboard', [
    'title' => 'Pengaturan',
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Pengaturan
          </h1>
        </div>
      </div>
    </div>
  </div>
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
        <div class="col-12 col-md-3 mb-3 mb-md-0">
          <div class="card card-mafindo sticky-top">
            <nav class="list-group list-group-flush">
              @unlessrole(['admin', 'pengurus-wilayah'])
                <a href="#email" class="list-group-item list-group-item-action toc-item">
                  Email
                </a>
              @endunlessrole
              <a href="password" class="list-group-item list-group-item-action toc-item">
                Password
              </a>
            </nav>
          </div>
        </div>
        <div class="col-12 col-md-9">
          <div class="vstack gap-3">
            @unlessrole(['admin', 'pengurus-wilayah'])
              <form method="POST" action="{{ route('user.updateEmail') }}" id="email" class="card card-mafindo">
                @csrf
                @method('PATCH')
                <div class="card-body">
                  <div class="row mb-4">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="email" class="form-label required">Email</label>
                      <x-form.input name="email" type="email" value="{{ $user->email }}" required />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="password" class="form-label required">Password</label>
                      <x-form.input name="password" type="password" required />
                    </div>
                  </div>
                  <div>
                    <button type="submit" class="btn btn-primary">Ganti Email</button>
                  </div>
                </div>
              </form>
            @endunlessrole
            <form method="POST" action="{{ route('user.updatePassword') }}" id="password" class="card card-mafindo">
              @csrf
              @method('PATCH')
              <div class="card-body">
                <div class="row mb-3">
                  <div class="col-12 col-md-6 mb-3 mb-md-0">
                    <label for="new-password" class="form-label required">Password Baru</label>
                    <x-form.input name="new_password" type="password" required />
                  </div>
                  <div class="col-12 col-md-6">
                    <label for="new-password-confirmation" class="form-label required">Konfirmasi Password Baru</label>
                    <x-form.input name="new_password_confirmation" type="password" required />
                  </div>
                </div>
                <div class="row mb-4">
                  <div class="col-12 col-md-6">
                    <label for="current-password" class="form-label required">Password Saat Ini</label>
                    <x-form.input name="current_password" type="password" required />
                  </div>
                </div>
                <div>
                  <button type="submit" class="btn btn-primary">Ganti Password</button>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
