@extends('layouts.base')

@push('styles')
  <style>
    body {
      background-color: var(--tblr-primary);
    }
  </style>
@endpush

@section('body')
  <div class="d-flex flex-column">
    <div class="page page-center">
      <div class="container container-tight py-4">
        <div class="text-center mb-4">
          <img src="{{ asset('static/img/mafindo-logo.png') }}" alt="Mafindo Logo" style="height: 56px">
        </div>
        @yield('content')
      </div>
    </div>
  </div>
@endsection
