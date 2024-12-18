@extends('layouts.dashboard', [
    'title' => 'Migrasi Data Relawan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex gap-2 justify-content-between align-items-center">
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
        <x-dt.datatable search="nama" total="{{ $tempUsers->count() }}">
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
          <table class="table table-vcenter card-table table-striped datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Wilayah</th>
                <th>No Relawan</th>
                <th>Timestamp</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($tempUsers as $user)
                <tr x-data="{ id: {{ $user->id }} }">
                  <td>
                    <a href="{{ route('migrasi.edit', $user->id) }}" class="text-decoration-underline text-dark">{{ $user->nama }}</a>
                  </td>
                  <td>{{ $user->email }}</td>
                  <td>{{ $user->branch?->nama }}</td>
                  <td>{{ $user->no_relawan }}</td>
                  <td>{{ $user->updated_at }}</td>
                  <td>
                    <div class="btn-list flex-nowrap justify-content-end">
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
  <x-modal-delete baseRoute="{{ route('migrasi.index') }}" />
@endsection
