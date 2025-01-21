@extends('layouts.dashboard', [
    'title' => 'Wilayah',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Wilayah
          </h1>
          <a href="{{ route('wilayah.create') }}" class="btn btn-primary">
            <x-lucide-plus class="icon" />
            Tambah
          </a>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <x-dt.datatable search="name" searchPlaceholder="Nama Wilayah" :collection="$branches">
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Total Pengguna</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($branches as $branch)
                <tr x-data="{ id: {{ $branch->id }} }">
                  <td data-label="Nama">
                    <a href="{{ route('wilayah.show', $branch->id) }}" class="fw-medium">
                      {{ $branch->name }}
                    </a>
                  </td>
                  <td data-label="Total Pengguna">
                    {{ $branch->users_count }} Pengguna
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      <a href="{{ route('wilayah.edit', $branch->id) }}" class="btn btn-icon">
                        <x-lucide-pencil class="icon text-blue" defer />
                      </a>
                      @if ($branch->users_count == 0)
                        <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn btn-icon" x-on:click="$dispatch('set-id', { id })">
                          <x-lucide-trash-2 class="icon text-red" defer />
                        </button>
                      @endif
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
  <x-modal-delete baseUrl="{{ route('wilayah.index') }}" />
@endsection
