@php
  $formName = ucwords(str_replace('-', ' ', $type));
  $formView = 'hris.registrasi.' . $type;

  if (in_array($type, ['relawan-baru', 'relawan-wilayah'])) {
      $formView = 'hris.registrasi.relawan';
  }

@endphp

@extends('layouts.unverified', [
    'title' => "Registrasi {$formName}",
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">Registrasi {{ $formName }}</h1>
          </div>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        @include($formView)
      </div>
    </div>
  </div>
@endsection
