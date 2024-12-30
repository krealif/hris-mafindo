@extends('layouts.dashboard', [
    'title' => 'Buat | Ajuan Surat',
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <div class="mb-1">
              <x-breadcrumb>
                <x-breadcrumb-item label="Surat" route="surat.index" />
                <x-breadcrumb-item label="Jenis" route="surat.template" />
              </x-breadcrumb>
            </div>
            <h1 class="page-title">
              Buat Ajuan Surat
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="col-12 col-md-10 col-lg-6">
        <form class="card card-mafindo" method="POST">
          @csrf
          <div class="card-header">
            <h2 class="card-title d-flex align-items-center gap-2">
              <x-lucide-file-text class="icon" />
              {{ $template->name }}
            </h2>
          </div>
          @can('create-letter-for-relawan')
            <div class="card-body">
              <div class="mb-3">
                <label for="penerima" class="form-label required">Penerima Surat</label>
                <select id="penerima" name="submitted_for_id" placeholder="Tuliskan nama"></select>
              </div>
            </div>
          @endcan
          @include('hris.surat.templates.' . $template->view)
          <div class="card-body btn-list">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('surat.index') }}" class="btn">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      window.TomSelect && new TomSelect('#penerima', {
        valueField: 'id',
        labelField: 'text',
        searchField: 'text',

        load: function(query, callback) {
          if (query.length < 3) return callback([]);

          fetch({{ Js::from(route('api.user')) }} + '?q=' + encodeURIComponent(query))
            .then(response => response.json())
            .then(data => callback(data.data || []))
            .catch(() => callback([]));
        }
      });
    })
  </script>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
@endpush
@push('scripts')
  <script src="{{ asset('static/vendor/tom-select.complete.min.js') }}"></script>
@endpush
