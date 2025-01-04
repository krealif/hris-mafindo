@extends('layouts.dashboard', [
    'title' => 'Ajuan Relawan Wilayah',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <h1 class="page-title">
            Ajuan Relawan Wilayah
          </h1>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <x-dt.datatable total="{{ $letters->count() }}">
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Pengirim / Tujuan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($letters as $letter)
                <tr>
                  <td data-label="Judul" style="max-width: 280px">
                    <a href="{{ route('surat.show', $letter->id) }}" class="fw-medium">
                      <x-lucide-file-text class="d-none d-lg-inline icon me-1" defer />
                      {{ $letter->title }}
                    </a>
                  </td>
                  <td data-label="Tipe">
                    @if ($letter->recipients->isEmpty())
                      <x-lucide-square-arrow-up-right class="icon me-1 text-blue" defer />
                      <span class="fw-medium">AJUAN</span>
                    @else
                      <x-lucide-square-arrow-up-right class="icon me-1 text-blue" defer />
                      <span class="fw-medium">DIAJUKAN</span> oleh Admin
                    @endif
                  </td>
                  <td>
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
                      {{ $letter->createdBy->nama }}
                    @endif
                  </td>
                  <td data-label="Status">
                    <x-badge class="fs-4" :case="$letter->status" />
                  </td>
                  <td data-label="Tanggal">
                    <div>{{ $letter->created_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-muted">{{ $letter->created_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
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
          @if ($letters->hasPages())
            <!-- Pagination -->
            <x-slot:pagination>
              {{ $letters->links() }}
            </x-slot>
          @endif
        </x-dt.datatable>
      </div>
    </div>
  </div>
@endsection
