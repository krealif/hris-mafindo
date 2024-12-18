@extends('layouts.unverified', [
    'title' => 'Registrasi Akun',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">Registrasi Akun</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        <div class="vstack gap-2">
          <a href="{{ route('registration.showForm', 'relawan-baru') }}" class="btn h3 py-3 m-0 justify-content-start">
            <x-lucide-arrow-up-right class="icon" />
            Relawan Baru
          </a>
          <a href="{{ route('registration.showForm', 'relawan-wilayah') }}" class="btn h3 py-3 m-0 justify-content-start">
            <x-lucide-arrow-up-right class="icon" />
            Relawan Wilayah
          </a>
          @if (strpos(auth()->user()->email, 'mafindo.or.id'))
            <a href="{{ route('registration.showForm', 'pengurus-wilayah') }}" class="btn h3 py-3 m-0 justify-content-start">
              <x-lucide-arrow-up-right class="icon" />
              Pengurus Wilayah
            </a>
          @endif
        </div>
      </div>
    </div>
  </div>
@endsection
