@extends('layouts.dashboard', [
    'title' => "Edit {$letter->title} | Permohonan Surat",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <div class="mb-1">
              <a href="{{ Auth::user()->hasRole('admin') ? route('surat.index') : route('surat.letterbox') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            <h1 class="page-title">
              Edit Permohonan Surat
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="col-12 col-md-10 col-lg-6">
        @if ($errors->any())
          <x-alert class="alert-danger">
            <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
            <ul class="mt-2 mb-0" style="margin-left: -1rem">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </x-alert>
        @endif
        <form class="card card-mafindo" method="POST" action="{{ route('surat.update', $letter->id) }}" enctype="multipart/form-data">
          @csrf
          @method('PATCH')
          @if ($letter?->status->value == 'revisi')
            <!-- Alasan dari Admin -->
            <div class="card-body bg-orange-lt text-dark">
              <h4 class="text-red text-uppercase m-0">Alasan {{ $letter?->status->value }}</h4>
              <p class="mb-2">Mohon untuk mengedit permohonan surat sesuai dengan arahan berikut</p>
              <hr class="my-2">
              <p>{{ $letter->message }}</p>
            </div>
          @endif
          <div class="card-body">
            @if (Auth::user()->can('create-letter-for-relawan') && $letter->recipients->isNotEmpty())
              <div class="mb-3">
                <label for="recipients" class="form-label required">Tujuan (Maks. 10)</label>
                <x-form.user-select id="recipients" name="recipients[]" multiple placeholder="Tuliskan nama relawan" required :selected="$letter->recipients->select(['id', 'nama'])" />
              </div>
            @endif
            <div class="mb-3">
              <label for="title" class="form-label required">Judul</label>
              <x-form.input name="title" type="text" value="{{ old('title', $letter->title) }}" required />
            </div>
            <div class="mb-3">
              <label for="body" class="form-label required">Deskripsi</label>
              <x-form.trix-editor name="body" required value="{!! old('body', $letter->body) !!}" />
            </div>
            @if (!$letter->attachment)
              <div class="mb-2" x-data="attachmentUpload">
                <label for="attachment" class="form-label">Upload Lampiran (opsional)</label>
                <div class="row g-2">
                  <div class="col">
                    <x-form.input name="attachment" x-ref="fileInput" x-on:change="handleFileUpload" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                    <span class="d-block text-muted mt-1">pdf, docx, jpg, jpeg, png (Max: 2 MB)</span>
                  </div>
                  <div class="col-12 col-sm-auto" x-show="filename">
                    <button x-on:click="cancelUpload" type="button" class="btn">
                      <x-lucide-circle-x class="icon text-red" />
                      Batal
                    </button>
                  </div>
                </div>
              </div>
            @else
              <div class="mb-2" x-data="{ _changeFile: false }">
                <label class="form-label mb-1">Lampiran</label>
                <p class="m-0 mb-2">(Perubahan akan diterapkan setelah Anda menyimpannya)</p>
                <div class="btn-group w-100 mb-3" role="group">
                  <input type="radio" x-model.boolean="_changeFile" class="btn-check" id="radio-1" autocomplete="off" value="false">
                  <label for="radio-1" type="button" class="btn">Berkas Ter-upload</label>
                  <input type="radio" x-model.boolean="_changeFile" class="btn-check" id="radio-2" autocomplete="off" value="true">
                  <label for="radio-2" type="button" class="btn">Ganti Berkas</label>
                </div>
                <div class="card card-body" x-show="_changeFile == false">
                  <a href="{{ route('surat.downloadAttachment', $letter->id) }}" class="d-inline-flex flex-wrap align-items-center gap-2" target="_blank">
                    <x-lucide-paperclip class="icon" />
                    {{ basename($letter->attachment) }}
                  </a>
                  <div class="btn-list mt-3" x-data="{ isDelete: false }">
                    <a href="{{ route('surat.downloadAttachment', $letter->id) }}" class="btn" target="_blank">
                      Download
                    </a>
                    <button x-on:click="isDelete = !isDelete" type="button" class="btn">
                      <input type="hidden" name="_isDeleteAttachment" x-model="isDelete">
                      <x-lucide-trash-2 class="icon text-red" />
                      <span x-text="isDelete ? 'Dihapus (Klik lagi untuk batal)' : 'Hapus'"></span>
                    </button>
                  </div>
                </div>
                <div x-data="attachmentUpload" x-show="_changeFile == true">
                  <label for="attachment" class="form-label visually-hidden">Ganti Berkas</label>
                  <div class="row g-2">
                    <div class="col">
                      <x-form.input name="attachment" x-ref="fileInput" x-on:change="handleFileUpload" type="file" accept=".pdf,.doc,.docx,.jpg,.jpeg,.png" />
                      <span class="d-block text-muted mt-1">pdf, docx, jpg, jpeg, png (Max: 2 MB)</span>
                    </div>
                    <div class="col-12 col-sm-auto" x-show="filename">
                      <button x-on:click="cancelUpload" type="button" class="btn">
                        <x-lucide-circle-x class="icon text-red" />
                        Batal
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            @endif
          </div>
          <div class="card-body btn-list">
            <button type="submit" class="btn btn-primary">Ajukan</button>
            <a href="{{ Auth::user()->hasRole('admin') ? route('surat.index') : route('surat.letterbox') }}" class="btn">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener('alpine:init', () => {
      Alpine.data('attachmentUpload', () => ({
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

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
@endpush
@push('scripts')
  <script src="{{ asset('static/vendor/tom-select.complete.min.js') }}" defer></script>
@endpush
