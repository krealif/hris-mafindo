@extends('layouts.dashboard', [
    'title' => 'Surat Relawan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Surat Relawan
          </h1>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <x-dt.datatable search="title" searchPlaceholder="Cari Judul Surat" :collection="$letters">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row gx-4 gy-3">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="relawan" class="form-label">Pengirim / Tujuan</label>
                <x-dt.user-filter name="relawan" selected="{{ request()->filter['relawan'] ?? '' }}" />
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
                <th>Relawan</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($letters as $letter)
                <tr>
                  <td data-label="Judul" class="letter-title">
                    <a href="{{ route('surat.show', $letter->id) }}" class="fw-medium">
                      <x-lucide-file-text class="d-none d-lg-inline icon me-1" defer />
                      {{ $letter->title }}
                    </a>
                  </td>
                  <td data-label="Tipe">
                    @if ($letter->recipients->isEmpty())
                      <x-lucide-square-arrow-up-right class="icon me-1 text-blue" defer />
                      <span class="fw-medium">PERMOHONAN</span> Relawan
                    @else
                      <x-lucide-square-arrow-down-right class="icon me-1 text-pink" defer />
                      <span class="fw-medium">DIBUAT</span> oleh Admin
                    @endif
                  </td>
                  <td>
                    @if ($letter->recipients->isNotEmpty())
                      <div style="max-width: 240px">
                        @foreach ($letter->recipients as $recipient)
                          @if ($recipient->id == Auth::id())
                            <b>{{ $recipient->nama }} (Saya)</b>
                          @else
                            {{ $recipient->nama }}
                          @endif
                          @unless ($loop->last)
                            |
                          @endunless
                        @endforeach
                      </div>
                    @else
                      {{ $letter->createdBy->nama }}
                    @endif
                  </td>
                  <td data-label="Tanggal">
                    <div>{{ $letter->updated_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-muted d-block d-md-none d-lg-block">{{ $letter->updated_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      @if ($letter->status->value == 'selesai')
                        <a href="{{ route('surat.download', $letter->id) }}" class="btn btn-icon" target="_blank">
                          <x-lucide-download class="icon text-green" defer />
                        </a>
                      @endif
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
