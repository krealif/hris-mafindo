@extends('layouts.dashboard', [
    'title' => "{$letter->template->name} | Ajuan Surat",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <div>
            @if ($letter->submitted_for_id == Auth::id() || $letter->submitted_by_id == Auth::id())
              <div class="mb-1">
                <a href="{{ route('surat.index') }}" class="btn btn-link px-0 py-1">
                  <x-lucide-arrow-left class="icon" />
                  Kembali
                </a>
              </div>
            @elseif (Auth::user()->can('create-letter-for-relawan'))
              <div class="mb-1">
                <a href="{{ route('surat.indexWilayah') }}" class="btn btn-link px-0 py-1">
                  <x-lucide-arrow-left class="icon" />
                  Kembali
                </a>
              </div>
            @endif
            <h1 class="page-title">
              Detail Ajuan
            </h1>
          </div>
          <div class="btn-list">
            @can('update', $letter)
              <a href="{{ route('surat.edit', $letter->id) }}" class="btn">
                <x-lucide-pen-line class="icon text-blue" />
                Edit
              </a>
            @endcan
            @can('destroy', $letter)
              <a class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-data="{ id: {{ $letter->id }} }" x-on:click="$dispatch('set-id', { id })">
                <x-lucide-trash-2 class="icon text-red" />
              </a>
            @endcan
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row g-3">
        <div class="col-12 col-md-6">
          <div class="card card-mafindo">
            <div class="card-body">
              <h2 class="d-flex flex-wrap align-items-center gap-2 m-0">
                <x-lucide-file-text class="icon flex-none" />
                <div>
                  {{ $letter->template->name }}
                  @if ($letter->submitted_for_id == Auth::id())
                    <span class="fw-normal">
                      {{ "dari {$letter->submittedBy->role?->label()}" }}
                    </span>
                  @endif
                </div>
              </h2>
              <div class="btn-list mt-3">
                @if ($letter->submitted_for_id == Auth::id())
                  <span class="badge bg-orange text-white hstack gap-2 fs-4">
                    <x-lucide-arrow-down-right class="icon" />
                    SURAT MASUK
                  </span>
                @elseif ($letter->submitted_by_id == Auth::id())
                  <span class="badge bg-blue text-white hstack gap-2 fs-4">
                    <x-lucide-arrow-up-right class="icon" />
                    AJUAN
                  </span>
                @endif
                <x-badge class="fs-4" :case="$letter->status" />
              </div>
              @if (in_array($letter?->status->value, ['revisi', 'ditolak']))
                <div class="card card-body mt-3">
                  <h4 class="text-red text-uppercase">Alasan</h4>
                  <p>{{ $letter->message }}</p>
                </div>
              @endif
              <div class="mt-3">
                <table class="datagrid">
                  <tr>
                    <th class="datagrid-title">Pengaju</th>
                    <td>{{ $letter->submittedBy->nama }}</td>
                  </tr>
                  @if ($letter->submitted_for_id)
                    <tr>
                      <th class="datagrid-title">Penerima</th>
                      <td>{{ $letter->submittedFor->nama }}</td>
                    </tr>
                  @endif
                  <tr>
                    <th class="datagrid-title">Dibuat</th>
                    <td>{{ $letter->created_at?->format('d/m/Y H:i') }}</td>
                  </tr>
                  <tr>
                    <th class="datagrid-title">Diperbarui</th>
                    <td>{{ $letter->updated_at?->format('d/m/Y H:i') }}</td>
                  </tr>
                </table>
              </div>
            </div>
            @if ($letter->file && (auth()->user()->can('review-letter') || $letter->status->value === 'selesai'))
              <div class="card-body">
                <div class="card card-body mb-3">
                  <a href="{{ route('surat.download', $letter->id) }}" class="fs-3 d-inline-flex flex-wrap align-items-center gap-2" target="_blank">
                    <x-lucide-file-down class="icon text-green" />
                    {{ basename($letter->file) }}
                  </a>
                  <div class="datagrid mt-3">
                    <div class="datagrid-item">
                      <div class="datagrid-title fs-4">Admin</div>
                      <div class="datagrid-content">{{ $letter->uploaded_by }}</div>
                    </div>
                    <div class="datagrid-item">
                      <div class="datagrid-title fs-4">Tanggal & Waktu</div>
                      <div class="datagrid-content">{{ $letter->uploaded_at }}</div>
                    </div>
                  </div>
                </div>
                <a href="{{ route('surat.download', $letter->id) }}" class="btn btn-success" target="_blank">Download</a>
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="vstack gap-3">
            @can('create-letter-for-relawan')
              @php
                // Check if the letter info should be visible to the Pengurus
                $isNotForCurrentUser = $letter->submitted_for_id != Auth::id();
                $isOwnSubmission = $letter->submitted_by_id == Auth::id() && !$letter->submitted_for_id;
              @endphp
              @if ($isNotForCurrentUser && !$isOwnSubmission)
                <div class="card card-mafindo">
                  <div class="card-header">
                    <h2 class="card-title d-flex align-items-center gap-2">
                      <x-lucide-user class="icon" />
                      {{ $letter->submitted_by_id == Auth::id() ? 'Penerima (Relawan)' : 'Relawan' }}
                    </h2>
                  </div>
                  <div class="card-body">
                    @if ($letter->submitted_by_id == Auth::id())
                      <div class="datagrid">
                        <x-datagrid-item title="Nama" content="{{ $letter->submittedFor->nama }}" />
                        <x-datagrid-item title="Wilayah" content="{{ $letter->submittedFor->branch?->nama }}" />
                        <x-datagrid-item title="Nomor Relawan" content="{{ $letter->submittedFor->no_relawan }}" />
                      </div>
                    @else
                      <div class="datagrid">
                        <x-datagrid-item title="Nama" content="{{ $letter->submittedBy->nama }}" />
                        <x-datagrid-item title="Wilayah" content="{{ $letter->submittedBy->branch?->nama }}" />
                        <x-datagrid-item title="Nomor Relawan" content="{{ $letter->submittedBy->no_relawan }}" />
                      </div>
                    @endif
                  </div>
                </div>
              @endif
            @endcan
            <div class="card card-mafindo">
              <div class="card-header">
                <h2 class="card-title d-flex align-items-center gap-2">
                  <x-lucide-letter-text class="icon" />
                  Isi Surat
                </h2>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  @foreach ($letter->content as $label => $value)
                    <x-datagrid-item title="{{ $label }}" content="{{ $value }}" />
                  @endforeach
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @can('destroy', $letter)
    <x-modal-delete baseUrl="{{ url('/surat/ajuan') }}" />
  @endcan
@endsection
