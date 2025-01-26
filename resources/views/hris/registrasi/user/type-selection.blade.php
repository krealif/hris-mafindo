@extends('layouts.unverified', [
    'title' => 'Registrasi Akun',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">Registrasi Akun</h1>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        <div class="vstack gap-2">
          <a href="{{ route('registrasi.showForm', 'relawan-baru') }}" class="card card-mafindo card-sm">
            <div class="card-body d-flex gap-2 align-items-center">
              <x-lucide-circle-user class="icon" defer />
              <span class="fs-3 fw-medium">Relawan Baru</span>
            </div>
          </a>
          <a href="{{ route('registrasi.showForm', 'relawan-wilayah') }}" class="card card-mafindo card-sm">
            <div class="card-body d-flex gap-2 align-items-center">
              <x-lucide-circle-user class="icon" defer />
              <span class="fs-3 fw-medium">Relawan Wilayah</span>
            </div>
          </a>
          @if (strpos(auth()->user()->email, 'mafindo.or.id'))
            <a href="{{ route('registrasi.showForm', 'pengurus-wilayah') }}" class="card card-mafindo card-sm">
              <div class="card-body d-flex gap-2 align-items-center">
                <x-lucide-circle-user class="icon" defer />
                <span class="fs-3 fw-medium">Pengurus Wilayah</span>
              </div>
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
