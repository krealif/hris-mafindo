@extends('layouts.dashboard', [
    'title' => 'Proses Permohonan Surat',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <h1 class="page-title">
            Proses Permohonan Surat
          </h1>
          @can('create', App\Models\Letter::class)
            <a href="{{ route('surat.create') }}" class="btn btn-primary">
              <x-lucide-plus class="icon" />
              Buat
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
        <x-dt.datatable search="title" searchPlaceholder="Cari judul surat" :collection="$letters">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row g-4">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="type" class="form-label">Tipe</label>
                <x-form.select name="type" selected="{{ request()->filter['type'] ?? '' }}" :showError=false :options="[
                    '' => 'Semua',
                    1 => 'Buat',
                    0 => 'Permohonan',
                ]" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="created_by" class="form-label">Pengirim</label>
                <x-dt.user-filter name="created_by" selected="{{ request()->filter['created_by'] ?? '' }}" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="recipient" class="form-label">Tujuan</label>
                <x-dt.user-filter name="recipient" selected="{{ request()->filter['recipient'] ?? '' }}" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="updated_at" class="form-label">Tanggal</label>
                <x-dt.date-filter name="updated_at" value="{{ request()->filter['updated_at'] ?? '' }}" />
              </div>
            </div>
          </x-slot>
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Pengirim</th>
                <th>Tujuan</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($letters as $letter)
                <tr x-data="{ id: {{ $letter->id }} }">
                  <td data-label="Judul" class="letter-title">
                    <a href="{{ route('surat.show', $letter->id) }}" class="fw-medium">
                      <x-lucide-file-text class="d-none d-lg-inline icon me-1" defer />
                      {{ $letter->title }}
                    </a>
                  </td>
                  <td data-label="Tipe">
                    @if ($letter->createdBy->hasRole('admin'))
                      <x-lucide-square-arrow-right class="icon me-1 text-pink" defer />
                      <strong class="fw-medium">BUAT PERMOHONAN</strong>
                    @else
                      <x-lucide-square-arrow-up-right class="icon me-1 text-blue" defer />
                      <strong class="fw-medium">PERMOHONAN</strong> {{ $letter->createdBy->role?->label() }}
                    @endif
                  </td>
                  <td data-label="Pengirim">
                    @if ($letter->created_by != Auth::id())
                      {{ $letter->createdBy->nama }}
                    @else
                      <x-lucide-circle-slash-2 class="icon" defer />
                    @endif
                  </td>
                  <td data-label="Tujuan">
                    @if ($letter->recipients->isNotEmpty())
                      <div style="max-width: 240px">
                        @foreach ($letter->recipients as $recipient)
                          @if ($loop->last)
                            {{ $recipient->nama }}
                          @else
                            {{ $recipient->nama }} |
                          @endif
                        @endforeach
                      </div>
                    @else
                      <x-lucide-circle-slash-2 class="icon" defer />
                    @endif
                  </td>
                  <td data-label="Tanggal">
                    <div>{{ $letter->updated_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-muted d-block d-md-none d-lg-block">{{ $letter->updated_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      @if ($letter->status->value == 'selesai')
                        <a href="{{ route('surat.download', $letter->id) }}" class="btn" target="_blank">
                          <x-lucide-download class="icon text-green" defer />
                          Download
                        </a>
                      @endif
                      @can('update', $letter)
                        <a href="{{ route('surat.edit', $letter->id) }}" class="btn">
                          <x-lucide-pen-line class="icon text-blue" defer />
                          Edit
                        </a>
                      @endcan
                      <a href="{{ route('surat.show', $letter->id) }}" class="btn">
                        <x-lucide-eye class="icon" defer />
                        Lihat
                      </a>
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
@endsection
