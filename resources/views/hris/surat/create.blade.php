@extends('layouts.dashboard', [
    'title' => 'Buat Permohonan Surat',
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
              Buat Permohonan Surat
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
        <form class="card card-mafindo" method="POST" x-data="{ _withRecipient: {{ old('_withRecipient', 'false') }} }" enctype="multipart/form-data">
          @csrf
          @if (!Auth::user()->hasRole('admin') && Auth::user()->can('create-letter-for-relawan'))
            <div class="card-body">
              <div class="btn-group w-100" role="group">
                <input type="radio" x-model.boolean="_withRecipient" class="btn-check" id="type-pengurus" autocomplete="off" value="false">
                <label for="type-pengurus" type="button" class="btn">Permohonan Saya</label>
                <input type="radio" x-model.boolean="_withRecipient" class="btn-check" id="type-relawan" autocomplete="off" value="true">
                <label for="type-relawan" type="button" class="btn">Permohonan untuk Relawan</label>
              </div>
            </div>
          @endif
          <div class="card-body">
            @can('create-letter-for-relawan')
              <div @if (!Auth::user()->hasRole('admin')) x-show="_withRecipient" @endif class="mb-3">
                <label for="recipients" class="form-label required">Tujuan (Maks. 10)</label>
                @if (Auth::user()->hasRole('admin'))
                  <x-form.user-select id="recipients" name="recipients[]" multiple placeholder="Tuliskan nama relawan" required />
                @else
                  <x-form.user-select id="recipients" name="recipients[]" multiple placeholder="Tuliskan nama relawan" x-bind:required='_withRecipient' />
                @endif
              </div>
            @endcan
            <div class="mb-3">
              <label for="title" class="form-label required">Judul</label>
              <x-form.input name="title" type="text" value="{{ old('title') }}" required />
            </div>
            <div class="mb-3">
              <label for="body" class="form-label required">Deskripsi</label>
              <x-form.trix-editor name="body" value="{!! old('body') !!}" required />
            </div>
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
