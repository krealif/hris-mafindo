@extends('layouts.base', [
    'title' => '403',
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
          <img src="{{ asset('static/img/403-img.webp') }}" alt="" class="card-img-top" style="aspect-ratio: 4/1;object-fit: cover">
          <div class="card-body">
            <h1 class="m-0">403</h1>
            <span class="d-none">
              {{ $exception->getMessage() }}
            </span>
            <h2 class="h3 fw-medium mb-2">Oops! Akses Ditolak.</h2>
            <p class="text-muted mb-4">Anda tidak memiliki izin untuk mengakses halaman ini. Pastikan Anda memiliki hak akses yang sesuai, atau kembali ke halaman utama.</p>
            <a href="{{ route('home') }}" class="btn btn-primary w-100">
              <x-lucide-home class="icon" />
              Halaman Utama
            </a>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
