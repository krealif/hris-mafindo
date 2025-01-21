@extends('layouts.base', [
    'title' => '419',
])

@push('styles')
  <style>
    body {
      background-color: var(--tblr-primary);
    }
  </style>
@endpush

@section('body')
  <div class="d-flex flex-column">
    <div class="page my-auto">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <img src="{{ asset('static/img/mafindo-logo.png') }}" alt="Mafindo Logo" style="height: 36px">
        </div>
        <div class="card card-mafindo">
          <div class="card-body">
            <h1 class="m-0">419</h1>
            <span class="d-none">
              {{ $exception->getMessage() }}
            </span>
            <h2 class="h3 fw-medium mb-2">Oops! Sesi Anda Telah Berakhir.</h2>
            <p class="text-muted mb-4">Hal ini terjadi karena Anda tidak aktif dalam waktu yang lama, atau token telah kedaluwarsa. Silakan login kembali untuk melanjutkan.</p>
            <a href="{{ route('login') }}" class="btn btn-primary w-100">
              <x-lucide-log-in class="icon" />
              Login Kembali
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
