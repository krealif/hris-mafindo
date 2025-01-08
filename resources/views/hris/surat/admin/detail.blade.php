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
              <a href="{{ url()->previous() == route('surat.indexHistory') ? route('surat.indexHistory') : route('surat.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            <h1 class="page-title">
              Detail Permohonan
            </h1>
          </div>
          <div class="btn-list">
            @can('destroy', $letter)
              @if (url()->previous() == route('surat.indexHistory'))
                <a class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-data="{ id: {{ $letter->id }} }" x-on:click="$dispatch('set-id', { id })">
                  <x-lucide-trash-2 class="icon text-red" />
                </a>
              @endif
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
            @if ($errors->any())
              <x-alert class="alert-danger m-0">
                <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                <ul class="mt-2 mb-0" style="margin-left: -1rem">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
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
                  @if ($letter->createdBy->hasRole('admin'))
                    <span class="badge bg-pink text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-right class="icon" />
                      BUAT PERMOHONAN
                    </span>
                  @else
                    <span class="badge bg-blue text-white hstack gap-2 fs-4">
                      <x-lucide-arrow-up-right class="icon" />
                      PERMOHONAN {{ $letter->createdBy->role?->label() }}
                    </span>
                  @endif
                  <x-badge class="fs-4" :case="$letter->status" />
                </div>
              </div>
              @if (in_array($letter?->status->value, ['revisi', 'ditolak']))
                <!-- Alasan dari Admin -->
                <div class="card-body bg-orange-lt">
                  <h4 class="text-red text-uppercase">Alasan {{ $letter?->status->value }}</h4>
                  <p class="text-dark">{{ $letter->message }}</p>
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
              @can('handleSubmission', $letter)
                <div class="card-body">
                  <!-- Admin panel -->
                  <ul class="nav nav-pills gap-2" role="tablist">
                    @if ($letter->result_file)
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
                    @if (!$letter->createdBy->hasRole('admin'))
                      <li class="nav-item" role="presentation">
                        <a href="#tab-revisi" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                          <x-lucide-file-pen-line class="icon text-orange me-2" />
                          Revisi
                        </a>
                      </li>
                    @endif
                    <li class="nav-item" role="presentation">
                      <a href="#tab-tolak" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-x class="icon text-red me-2" />
                        Tolak
                      </a>
                    </li>
                  </ul>
                </div>
                <div class="tab-content">
                  @if ($letter->result_file)
                    <div id="tab-kirim" class="tab-pane active show">
                      <form method="POST" action="{{ route('surat.approve', $letter->id) }}" class="card-body border-top">
                        @csrf
                        @method('PATCH')
                        <div class="card card-body mb-3">
                          <a href="{{ route('surat.download', $letter->id) }}" class="d-inline-flex flex-wrap align-items-center gap-2" target="_blank">
                            <x-lucide-file-text class="icon text-indigo" />
                            {{ basename($letter->result_file) }}
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
                        </div>
                      </form>
                    </div>
                  @endif
                  <div id="tab-upload" class="tab-pane">
                    <form method="POST" action="{{ route('surat.uploadResult', $letter->id) }}" class="card-body border-top" enctype="multipart/form-data">
                      @csrf
                      @method('PATCH')
                      <div class="mb-3">
                        <label for="admin" class="form-label required">Nama Admin</label>
                        <x-form.input name="admin" type="text" required />
                      </div>
                      <div class="mb-4" x-data="fileUpload">
                        <label for="file" class="form-label required">File Surat</label>
                        <div class="row g-2">
                          <div class="col">
                            <x-form.input name="file" x-ref="fileInput" x-on:change="handleFileUpload" type="file" accept=".pdf,.doc,.docx" required />
                            <span class="d-block text-muted mt-1">pdf,docx (Max: 2 MB)</span>
                          </div>
                          <div class="col-12 col-sm-auto" x-show="filename">
                            <button x-on:click="cancelUpload" type="button" class="btn">
                              <x-lucide-circle-x class="icon text-red" />
                              Batal
                            </button>
                          </div>
                        </div>
                      </div>
                      <button class="btn btn-primary" type="submit">Upload</button>
                    </form>
                  </div>
                  @if (!$letter->createdBy->hasRole('admin'))
                    <div id="tab-revisi" class="tab-pane">
                      <form method="POST" action="{{ route('surat.requestRevision', $letter->id) }}" class="card-body border-top">
                        @csrf
                        @method('PATCH')
                        <div class="mb-4">
                          <label for="message" class="form-label required">Alasan</label>
                          <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan revisi" required />
                        </div>
                        <button class="btn btn-primary" type="submit">Revisi Permohonan</button>
                      </form>
                    </div>
                  @endif
                  <div id="tab-tolak" class="tab-pane">
                    <form method="POST" action="{{ route('surat.reject', $letter->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <div class="mb-4">
                        <label for="message" class="form-label required">Alasan</label>
                        <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan penolakan" required />
                      </div>
                      <button class="btn btn-primary" type="submit">Tolak Permohonan</button>
                    </form>
                  </div>
                </div>
              @endcan
            </div>
          </div>
          <div class="col-12 col-md-6">
            <div class="vstack gap-3">
              @if ($letter->created_by != Auth::id())
                <div class="card card-mafindo">
                  <div class="card-header">
                    <h2 class="card-title d-flex align-items-center gap-2">
                      <x-lucide-user class="icon" />
                      Pengirim ({{ $letter->createdBy->role?->label() }})
                    </h2>
                  </div>
                  <div class="card-body">
                    <div class="datagrid">
                      <x-datagrid-item title="Nama" content="{{ $letter->createdBy->nama }}" />
                      <x-datagrid-item title="Wilayah" content="{{ $letter->createdBy->branch?->nama }}" />
                      <x-datagrid-item title="Nomor Relawan" content="{{ $letter->createdBy->no_relawan }}" />
                    </div>
                  </div>
                </div>
              @endif
              @if ($letter->recipients->isNotEmpty())
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
                        <th>Role</th>
                        <th>Wilayah</th>
                        <th>Nomor Relawan</th>
                        <th class="w-1"></th>
                      </tr>
                    </thead>
                    <tbody>
                      @php
                        $letter->recipients->load(['branch', 'roles']);
                      @endphp
                      @foreach ($letter->recipients as $recipient)
                        <tr>
                          <td>{{ $recipient->nama }}</td>
                          <td>{{ $recipient->role?->label() }}</td>
                          <td>{{ $recipient->branch?->nama }}</td>
                          <td>{{ $recipient->no_relawan }}</td>
                        </tr>
                      @endforeach
                    </tbody>
                  </table>
                </div>
              @endif
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
  @can('destroy', $letter)
    @if (url()->previous() == route('surat.indexHistory'))
      <x-modal-delete baseUrl="{{ route('surat.index') }}" />
    @endif
  @endcan
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('fileUpload', () => ({
        filename: '',
        cancelUpload() {
          this.filename = '';
          this.$refs.fileInput.value = '';
        },
        handleFileUpload(event) {
          const fileInput = this.$refs.fileInput;
          this.filename = fileInput.files.length > 0 ? fileInput.files[0].name : '';
        }
      }));
    });
  </script>
@endsection
