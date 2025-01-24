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
            <h2 class="h3 fw-medium mb-2">Oops! Sesi Anda Telah Berakhir.</h2>
            <p class="text-muted mb-4">Hal ini terjadi karena Anda tidak aktif dalam waktu yang lama, atau token telah kedaluwarsa.</p>
            @php
              $siteDomain = parse_url(route('home'), PHP_URL_HOST);
              $refererDomain = parse_url(request()->header('referer'), PHP_URL_HOST);
            @endphp
            @if ($refererDomain && $siteDomain == $refererDomain)
              <a href="{{ request()->header('referer') }}" class="btn btn-primary w-100">
                <x-lucide-rotate-cw class="icon" />
                Refresh
              </a>
            @else
              <a href="{{ route('home') }}" class="btn btn-primary w-100">
                <x-lucide-home class="icon" />
                Halaman Utama
              </a>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
