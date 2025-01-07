@extends('layouts.dashboard', [
    'title' => 'Migrasi Data Relawan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <div>
            <h1 class="page-title">
              Migrasi Data Relawan
            </h1>
            <p class="text-muted m-0 mt-1">
              Integrasikan data lama relawan ke dalam sistem.
            </p>
          </div>
          <a href="{{ route('migrasi.create') }}" class="btn btn-primary">
            <x-lucide-plus class="icon" />
            Tambah
          </a>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <x-dt.datatable search="nama" searchPlaceholder="Nama relawan" total="{{ $tempUsers->count() }}">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row g-4">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="email" class="form-label">Email</label>
                <x-form.input name="email" type="text" :showError=false value="{{ request()->filter['email'] ?? '' }}" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="no_relawan" class="form-label">No Relawan</label>
                <x-form.input name="no_relawan" type="text" :showError=false value="{{ request()->filter['no_relawan'] ?? '' }}" />
              </div>
            </div>
          </x-slot>
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Wilayah</th>
                <th>No Relawan</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tempUsers as $user)
                <tr x-data="{ id: {{ $user->id }} }">
                  <td data-label="Nama">
                    <a href="{{ route('migrasi.edit', $user->id) }}" class="fw-medium">
                      <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                      {{ $user->nama }}
                    </a>
                  </td>
                  <td data-label="Email">{{ $user->email }}</td>
                  <td data-label="Wilayah">{{ $user->branch?->nama ?? '-' }}</td>
                  <td data-label="No Relawan">{{ $user->no_relawan ?? '-' }}</td>
                  <td data-label="Tanggal">
                    <div>{{ $user->updated_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-muted d-block d-md-none d-lg-block">{{ $user->updated_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      <a href="{{ route('migrasi.edit', $user->id) }}" class="btn">
                        <x-lucide-pencil class="icon text-blue" defer />
                        Edit
                      </a>
                      <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn" x-on:click="$dispatch('set-id', { id })">
                        <x-lucide-trash-2 class="icon text-red" defer />
                        Hapus
                      </button>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @if ($tempUsers->hasPages())
            <!-- Pagination -->
            <x-slot:pagination>
              {{ $tempUsers->links() }}
            </x-slot>
          @endif
        </x-dt.datatable>
      </div>
    </div>
  </div>
  <x-modal-delete baseUrl="{{ route('migrasi.index') }}" />
@endsection
