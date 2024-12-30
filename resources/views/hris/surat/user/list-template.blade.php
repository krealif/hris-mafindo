@extends('layouts.dashboard', [
    'title' => 'Jenis Surat | Ajuan Surat',
])

@section('content')
  <div class="page-wrapper">
    <!-- Judul Halaman -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <div class="mb-1">
              <x-breadcrumb>
                <x-breadcrumb-item label="Surat" route="surat.index" />
              </x-breadcrumb>
            </div>
            <h1 class="page-title">
              Jenis Surat
            </h1>
            <p class="text-muted m-0 mt-1">
              Pilih jenis surat untuk diajukan
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="vstack gap-2">
          @foreach ($templates as $template)
            <a href="{{ route('surat.create', $template->view) }}" class="btn px-4 py-3 justify-content-start">
              <div class="d-flex flex-wrap align-items-center">
                <x-lucide-file-text class="icon" defer />
                <h3 class="m-0">{{ $template->name }}</h3>
              </div>
            </a>
          @endforeach
        </div>
      </div>
    </div>
  </div>
@endsection
