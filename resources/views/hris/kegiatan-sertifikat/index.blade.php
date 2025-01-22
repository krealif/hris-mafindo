@extends('layouts.dashboard', [
    'title' => "{$event->name} | Sertifikat Kegiatan",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div>
          <a href="{{ route('kegiatan.show', $event->id) }}" class="btn btn-link px-0 py-1 mb-1">
            <x-lucide-arrow-left class="icon" />
            Kembali
          </a>
          <h1 class="page-title">
            Detail Kegiatan
          </h1>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        @include('hris.kegiatan._tabs-detail')
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <div class="row g-3">
          <div class="col-12">
            <div class="card card-mafindo">
              <div class="card-body">
                <h2 class="m-0">
                  Sertifikat
                </h2>
                <h3 class="fw-normal">{{ $event->name }}</h3>
                @if (empty(request()->filter))
                  <div class="d-flex flex-wrap gap-2 align-items-center mt-3">
                    <x-lucide-award class="icon" defer />
                    <span>{{ "{$userCertificates->total()} / {$event->participants_count} Sertifikat Dibuat" }}</span>
                  </div>
                @endif
              </div>
            </div>
          </div>
          <div class="col-12">
            <x-dt.datatable search="nama" searchPlaceholder="Nama Relawan" :collection="$userCertificates">
              @if ($userCertificates->total() < $event->participants_count)
                <x-slot:actions>
                  <a href="{{ route('sertifikat.create', $event->id) }}" class="btn btn-primary">
                    <x-lucide-plus class="icon" />
                    Tambah
                  </a>
                </x-slot>
              @endif
              <!-- Table Body -->
              <table class="table table-vcenter card-table table-mobile-md datatable">
                <thead class="table-primary">
                  <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Relawan</th>
                    <th>Tanggal</th>
                    <th class="w-1"></th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($userCertificates as $user)
                    <tr x-data="{ id: {{ $user->pivot->id }} }">
                      <td data-label="Nama">
                        <a href="{{ route('user.profile', $user->id) }}" target="_blank">
                          <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                          {{ $user->nama }}
                        </a>
                      </td>
                      <td data-label="Email">{{ $user->email }}</td>
                      <td data-label="No Relawan">{{ $user->no_relawan ?? '-' }}</td>
                      <td data-label="Tanggal">{{ $user->pivot->created_at?->format('d M Y / H:i') }}</td>
                      <td data-label="Aksi">
                        <div class="btn-list flex-nowrap justify-content-md-end">
                          <a href="{{ route('sertifikat.downloadForAdmin', $user->pivot->id) }}" class="btn" target="_blank">
                            <x-lucide-file-badge class="icon text-green" defer />
                            Buka
                          </a>
                          <a href="{{ route('sertifikat.edit', [$event->id, $user->pivot->id]) }}" class="btn btn-icon">
                            <x-lucide-pencil class="icon text-blue" defer />
                          </a>
                          <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn btn-icon" x-on:click="$dispatch('set-id', { id })">
                            <x-lucide-trash-2 class="icon text-red" defer />
                          </button>
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
    </div>
  </div>
  <x-modal-delete baseUrl="{{ route('sertifikat.index', $event->id) }}" />
@endsection
