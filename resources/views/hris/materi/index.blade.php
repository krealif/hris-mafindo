@extends('layouts.dashboard', [
    'title' => 'Materi',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Kumpulan Materi
          </h1>
          @can('create', App\Models\Material::class)
            <a href="{{ route('materi.create') }}" class="btn btn-primary">
              <x-lucide-plus class="icon" />
              Tambah
            </a>
          @endcan
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <x-dt.datatable search="title" searchPlaceholder="Cari Judul Materi" :collection="$materials">
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Judul</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($materials as $material)
                <tr x-data="{ id: {{ $material->id }} }">
                  <td data-label="Nama">
                    <a href="{{ $material->url }}" target="_blank">
                      <x-lucide-book-text class="d-none d-lg-inline icon me-1" defer />
                      {{ $material->title }}
                    </a>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-end">
                      <a href="{{ $material->url }}" class="btn" target="_blank">
                        <x-lucide-square-arrow-out-up-right class="icon" />
                        Buka
                      </a>
                      @canany(['update', 'delete'], $material)
                        <a href="{{ route('materi.edit', $material->id) }}" class="btn btn-icon">
                          <x-lucide-pen-line class="icon text-blue" defer />
                        </a>
                        <button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-on:click="$dispatch('set-id', { id })">
                          <x-lucide-trash-2 class="icon text-red" defer />
                        </button>
                      @else
                        <button class="btn" data-bs-toggle="modal" data-bs-target="#modal-tautan" x-on:click="$dispatch('set-link', { url: '{{ $material->url }}'  })">
                          <x-lucide-link class="icon text-blue" />
                          Tautan
                        </button>
                      @endcanany
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </x-dt.datatable>
      </div>
    </div>
  </div>
  <x-modal-delete baseUrl="{{ url('/materi') }}" />
  <x-modal-tautan />
@endsection
