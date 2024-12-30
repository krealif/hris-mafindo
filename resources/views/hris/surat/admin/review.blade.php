@extends('layouts.dashboard', [
    'title' => "{$letter->template->name} | Review Ajuan Surat",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <div>
            <div class="mb-1">
              <a href="{{ route('surat.rev.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            <h1 class="page-title">
              Review Ajuan
            </h1>
          </div>
          <div class="btn-list">
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
        <div class="col-12 hidden-if-empty">
          @if (flash()->message)
            <x-alert class="{{ flash()->class }}">
              {{ flash()->message }}
            </x-alert>
          @endif
          @if ($errors->any())
            <!-- Validation error -->
            <x-alert class="alert-danger">
              Error! Tolong periksa kembali data yang Anda masukkan.
            </x-alert>
          @endif
        </div>
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
                @if ($letter->submitted_by_id == Auth::id())
                  <span class="badge bg-pink text-white hstack gap-2 fs-4">
                    <x-lucide-arrow-down-right class="icon" />
                    BUAT
                  </span>
                @else
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

            <div class="card-body">
              <ul class="nav nav-pills gap-2" role="tablist">
                @if ($letter->file)
                  <li class="nav-item" role="presentation">
                    <a href="#tab-kirim" class="btn active" data-bs-toggle="tab" aria-selected="true" role="tab">
                      <x-lucide-send class="icon text-indigo me-2" />
                      Kirim
                    </a>
                  </li>
                @endif
                <li class="nav-item" role="presentation">
                  <a href="#tab-upload" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                    <x-lucide-upload class="icon text-blue me-2" />
                    Upload
                  </a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="#tab-revisi" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                    <x-lucide-file-pen-line class="icon text-orange me-2" />
                    Revisi
                  </a>
                </li>
                <li class="nav-item" role="presentation">
                  <a href="#tab-tolak" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                    <x-lucide-circle-x class="icon text-red me-2" />
                    Tolak
                  </a>
                </li>
              </ul>
            </div>
            <div class="tab-content">
              @if ($letter->file)
                <div id="tab-kirim" class="tab-pane active show">
                  <form method="POST" class="card-body border-top">
                    @csrf
                    @method('PATCH')
                    <div class="card card-body mb-3">
                      <a href="{{ route('surat.download', $letter->id) }}" class="fs-3 d-inline-flex flex-wrap align-items-center gap-2" target="_blank">
                        <x-lucide-file-text class="icon text-indigo" />
                        {{ basename($letter->file) }}
                      </a>
                      <div class="datagrid mt-3">
                        <div class="datagrid-item">
                          <div class="datagrid-title fs-4">Diupload Oleh</div>
                          <div class="datagrid-content">{{ $letter->uploaded_by }}</div>
                        </div>
                        <div class="datagrid-item">
                          <div class="datagrid-title fs-4">Tanggal & Waktu</div>
                          <div class="datagrid-content">{{ $letter->uploaded_at }}</div>
                        </div>
                      </div>
                    </div>
                    <div class="btn-list">
                      <button class="btn btn-primary" type="submit">Kirim</button>
                      <a href="{{ route('surat.download', $letter->id) }}" class="btn" target="_blank">Download</a>
                    </div>
                  </form>
                </div>
              @endif
              <div id="tab-upload" class="tab-pane">
                <form method="POST" action="{{ route('surat.rev.upload', $letter->id) }}" class="card-body border-top" enctype="multipart/form-data">
                  @csrf
                  @method('PATCH')
                  <div class="mb-3">
                    <label for="admin" class="form-label required">Admin</label>
                    <x-form.input name="admin" type="text" placeholder="Tuliskan nama admin" required />
                  </div>
                  <div class="mb-4">
                    <label for="file" class="form-label required">Upload Surat (PDF)</label>
                    <x-form.input name="file" type="file" required />
                  </div>
                  <button class="btn btn-primary" type="submit">Simpan</button>
                </form>
              </div>
              <div id="tab-revisi" class="tab-pane">
                <form method="POST" class="card-body border-top">
                  @csrf
                  <div class="mb-3">
                    <label for="admin" class="form-label required">Admin</label>
                    <x-form.input name="admin" type="text" placeholder="Tuliskan nama admin" required />
                  </div>
                  <button class="btn btn-primary" type="submit">Simpan</button>
                </form>
              </div>
              <div id="tab-tolak" class="tab-pane">
                tolak
              </div>
            </div>

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
                      {{ $letter->submitted_by_id == Auth::id() ? 'Penerima (Relawan)' : 'Pengaju' }}
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
