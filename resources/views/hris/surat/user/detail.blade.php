@extends('layouts.dashboard', [
    'title' => "{$letter->title} | Permohonan Surat",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            @if (url()->previous() == route('surat.indexWilayah'))
              <a href="{{ route('surat.indexWilayah') }}" class="btn btn-link px-0 py-1 mb-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @elseif ($letter->created_by == Auth::id() || $letter->recipients->contains('id', Auth::id()))
              <a href="{{ route('surat.letterbox') }}" class="btn btn-link px-0 py-1 mb-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @endif
            <h1 class="page-title">
              Detail Permohonan
            </h1>
          </div>
          <div class="btn-list">
            @can('update', $letter)
              <a href="{{ route('surat.edit', $letter->id) }}" class="btn">
                <x-lucide-pen-line class="icon text-blue" />
                Edit
              </a>
            @endcan
            @can('delete', $letter)
              <a class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-data="{ id: {{ $letter->id }} }" x-on:click="$dispatch('set-id', { id })">
                <x-lucide-trash-2 class="icon text-red" />
              </a>
            @endcan
          </div>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        <div>
          @if (flash()->message)
            <x-alert type="{{ flash()->class }}">
              {{ flash()->message }}
            </x-alert>
          @endif
        </div>
        <div class="row g-3">
          <div class="col-12 col-md-6">
            <div class="card card-mafindo">
              <!-- Judul surat -->
              <div class="card-body">
                <div class="row g-3 ">
                  <div class="col-12 col-lg-auto">
                    <img src="{{ asset('static/img/doc-placeholder.png') }}" class="avatar avatar-lg" />
                  </div>
                  <div class="col">
                    <span class="page-pretitle fs-5">
                      Judul
                    </span>
                    <h2 class="card-title h3 m-0">{{ $letter->title }}</h2>
                  </div>
                </div>
                <div class="btn-list mt-3">
                  @if ($letter->created_by == Auth::id())
                    @if ($letter->recipients->isEmpty())
                      <span class="badge bg-blue text-white hstack gap-2 fs-4">
                        <x-lucide-arrow-up-right class="icon" />
                        PERMOHONAN Saya
                      </span>
                    @else
                      <span class="badge bg-blue text-white hstack gap-2 fs-4">
                        <x-lucide-arrow-up-right class="icon" />
                        PERMOHONAN untuk Relawan
                      </span>
                    @endif
                  @elseif ($letter->recipients->contains('id', Auth::id()))
                    <span class="badge bg-pink text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-down-right class="icon" />
                      SURAT dari {{ $letter->createdBy->role?->label() }}
                    </span>
                  @elseif ($letter->recipients->isEmpty())
                    <span class="badge bg-blue text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-up-right class="icon" />
                      PERMOHONAN Relawan
                    </span>
                  @else
                    <span class="badge bg-pink text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-down-right class="icon" />
                      DIBUAT oleh Admin
                    </span>
                  @endif
                  <x-badge class="d-flex fs-4" :case="$letter->status" />
                </div>
              </div>
              @if (in_array($letter?->status->value, ['revisi', 'ditolak']))
                <!-- Alasan dari Admin -->
                <div class="card-body bg-orange-lt text-dark">
                  <h4 class="text-red text-uppercase m-0">Alasan {{ $letter?->status->value }}</h4>
                  @if ($letter->status->value == 'revisi')
                    <p class="m-0">Mohon untuk mengedit permohonan surat sesuai dengan arahan berikut</p>
                    <hr class="m-0 mt-2">
                  @endif
                  <p class="mt-2">{{ $letter->message }}</p>
                </div>
              @endif
              <!-- Informasi surat -->
              <div class="card-body">
                <div class="datagrid">
                  <div class="datagrid-item">
                    <div class="datagrid-title">Pengirim</div>
                    <div class="datagrid-content">
                      {{ $letter->createdBy->nama }}
                      @if ($letter->created_by == Auth::id())
                        <strong class="fw-medium">(Saya)</strong>
                      @endif
                    </div>
                  </div>
                  @if ($letter->recipients->isNotEmpty())
                    <div class="datagrid-item">
                      <div class="datagrid-title">Tujuan</div>
                      <div class="datagrid-content">
                        @if ($letter->recipients->contains('id', Auth::id()))
                          {{ Auth::user()->nama }} <strong class="fw-medium">(Saya)</strong>
                        @else
                          {{ $letter->recipients->count() }} Orang
                        @endif
                      </div>
                    </div>
                  @endif
                  <x-datagrid-item title="Dibuat" content="{{ $letter->created_at?->translatedFormat('d F Y / H:i') }}" />
                  <x-datagrid-item title="Diperbarui" content="{{ $letter->updated_at?->translatedFormat('d F Y / H:i') }}" />
                </div>
              </div>
              @if ($letter->result_file && $letter->status->value === 'selesai')
                <!-- Download hasil permohonan surat -->
                <div class="card-body">
                  <div class="card card-body mb-3">
                    <a href="{{ route('surat.download', $letter->id) }}" class="fs-3 d-inline-flex flex-wrap align-items-center gap-2" target="_blank">
                      <x-lucide-file-down class="icon text-green" />
                      {{ basename($letter->result_file) }}
                    </a>
                    <div class="datagrid datagrid-h mt-3">
                      <x-datagrid-item title="Admin" content="{{ $letter->uploaded_by }}" />
                      <x-datagrid-item title="Tanggal" content="{{ $letter->uploaded_at?->translatedFormat('d F Y / H:i') }}" />
                    </div>
                  </div>
                  <a href="{{ route('surat.download', $letter->id) }}" class="btn btn-success" target="_blank">Download Surat</a>
                </div>
              @endif
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="vstack gap-3">
              @haspermission('view-relawan-letter')
                @if ($letter->recipients->isNotEmpty())
                  {{-- Ketika Permohonan dibuat oleh Pengurus/Admin untuk Relawan/Pengurus --}}
                  <div class="card card-mafindo">
                    <div class="card-header">
                      <h2 class="card-title d-flex align-items-center gap-2">
                        <x-lucide-forward class="icon" />
                        Tujuan
                      </h2>
                    </div>
                    <table class="table table-vcenter card-table">
                      <thead>
                        <tr>
                          <th>Nama</th>
                          <th></th>
                        </tr>
                      </thead>
                      <tbody>
                        @foreach ($letter->recipients as $recipient)
                          @if ($recipient->branch_id == Auth::user()->branch_id)
                            <tr>
                              <td>
                                @if ($recipient->id == Auth::id())
                                  {{ $recipient->nama }} <strong class="fw-medium">(Saya)</strong>
                                @else
                                  <a href="{{ route('user.profile', $recipient->id) }}" target="_blank">
                                    {{ $recipient->nama }}
                                  </a>
                                @endif
                              </td>
                              <td>{{ $recipient->no_relawan }}</td>
                            </tr>
                          @endif
                        @endforeach
                      </tbody>
                    </table>
                  </div>
                @elseif ($letter->created_by != Auth::id())
                  {{-- Ketika Permohonan dibuat oleh Relawan --}}
                  <div class="card card-mafindo">
                    <div class="card-header">
                      <h2 class="card-title d-flex align-items-center gap-2">
                        <x-lucide-user class="icon" />
                        Detail Relawan
                      </h2>
                    </div>
                    <div class="card-body">
                      <div class="datagrid">
                        <div class="datagrid-item">
                          <div class="datagrid-title">Nama</div>
                          <div class="datagrid-content">
                            <a href="{{ route('user.profile', $letter->created_by) }}" target="_blank">
                              {{ $letter->createdBy->nama }}
                            </a>
                          </div>
                        </div>
                        <x-datagrid-item title="Nomor Relawan" content="{{ $letter->createdBy->no_relawan }}" />
                      </div>
                    </div>
                  </div>
                @endif
              @endhaspermission
              <div class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title d-flex align-items-center gap-2">
                    <x-lucide-letter-text class="icon" />
                    Deskripsi
                  </h2>
                </div>
                <div class="card-body">
                  {!! $letter->body !!}
                </div>
                @if ($letter->attachment)
                  <div class="card-body">
                    <h4 class="text-uppercase mb-3">Lampiran</h4>
                    <div class="mb-2">
                      <a href="{{ route('surat.downloadAttachment', $letter->id) }}" class="d-inline-flex flex-wrap align-items-center gap-2" target="_blank">
                        <x-lucide-paperclip class="icon" />
                        {{ basename($letter->attachment) }}
                      </a>
                    </div>
                    <div>
                      <a href="{{ route('surat.downloadAttachment', $letter->id) }}" class="btn" target="_blank">Lihat</a>
                    </div>
                  </div>
                @endif
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @can('delete', $letter)
    <x-modal-delete baseUrl="{{ route('surat.index') }}" />
  @endcan
@endsection
