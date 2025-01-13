@extends('layouts.dashboard', [
    'title' => "{$letter->title} | Permohonan Surat",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <div>
            <div class="mb-1">
              @if (url()->previous() == route('surat.indexWilayah'))
                <a href="{{ route('surat.indexWilayah') }}" class="btn btn-link px-0 py-1">
                  <x-lucide-arrow-left class="icon" />
                  Kembali
                </a>
              @elseif ($letter->created_by == Auth::id() || $letter->recipients->contains('id', Auth::id()))
                <a href="{{ route('surat.letterbox') }}" class="btn btn-link px-0 py-1">
                  <x-lucide-arrow-left class="icon" />
                  Kembali
                </a>
              @endif
            </div>
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
        <div class="row g-3">
          <div class="col-12 hidden-if-empty order-first">
            @if (flash()->message)
              <x-alert type="{{ flash()->class }}" class="m-0">
                {{ flash()->message }}
              </x-alert>
            @endif
          </div>
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
                    <span class="badge bg-orange text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-down-right class="icon" />
                      SURAT dari {{ $letter->createdBy->role?->label() }}
                    </span>
                  @elseif ($letter->recipients->isEmpty())
                    <span class="badge bg-blue text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-up-right class="icon" />
                      PERMOHONAN
                    </span>
                  @else
                    <span class="badge bg-blue text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-up-right class="icon" />
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
                    <div class="datagrid-title fs-4">Pengirim</div>
                    <div class="datagrid-content">
                      {{ $letter->createdBy->nama }}
                      @if ($letter->created_by == Auth::id())
                        <strong class="fw-medium">(Saya)</strong>
                      @endif
                    </div>
                  </div>
                  @if ($letter->recipients->isNotEmpty())
                    <div class="datagrid-item">
                      <div class="datagrid-title fs-4">Tujuan</div>
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
                  <a href="{{ route('surat.download', $letter->id) }}" class="btn btn-success" target="_blank">Download Surat</a>
                </div>
              @endif
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="vstack gap-3">
              @haspermission('create-letter-for-relawan')
                @php
                  // Check if the letter info should be visible to the Pengurus
                  $isNotForCurrentUser = !$letter->recipients->contains('id', Auth::id());
                  $isOwnSubmission = $letter->created_by == Auth::id() && $letter->recipients->isEmpty();
                @endphp
                @if ($isNotForCurrentUser && !$isOwnSubmission)
                  <div class="card card-mafindo">
                    <div class="card-header">
                      <h2 class="card-title d-flex align-items-center gap-2">
                        @if ($letter->recipients->isEmpty())
                          <x-lucide-user class="icon" />
                          Detail Relawan
                        @else
                          <x-lucide-forward class="icon" />
                          Tujuan
                        @endif
                      </h2>
                    </div>
                    @if ($letter->recipients->isEmpty())
                      <div class="card-body">
                        <div class="datagrid">
                          <x-datagrid-item title="Nama" content="{{ $letter->createdBy->nama }}" />
                          <x-datagrid-item title="Wilayah" content="{{ $letter->createdBy->branch?->nama }}" />
                          <x-datagrid-item title="Nomor Relawan" content="{{ $letter->createdBy->no_relawan }}" />
                        </div>
                      </div>
                    @else
                      <table class="table table-vcenter card-table">
                        <thead>
                          <tr>
                            <th>Nama</th>
                            <th>Wilayah</th>
                            <th>Nomor Relawan</th>
                            <th class="w-1"></th>
                          </tr>
                        </thead>
                        <tbody>
                          @php
                            $letter->recipients->load('branch');
                          @endphp
                          @foreach ($letter->recipients as $recipient)
                            <tr>
                              <td>{{ $recipient->nama }}</td>
                              <td>{{ $recipient->branch?->nama }}</td>
                              <td>{{ $recipient->no_relawan }}</td>
                            </tr>
                          @endforeach
                        </tbody>
                      </table>
                    @endif
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
                    <h4 class="text-uppercase">Lampiran</h4>
                    <a href="{{ route('surat.downloadAttachment', $letter->id) }}" class="d-inline-flex flex-wrap align-items-center gap-2 text-decoration-underline"
                      target="_blank">
                      <x-lucide-file-text class="icon" />
                      {{ basename($letter->attachment) }} [Download]
                    </a>
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
