@extends('layouts.dashboard', [
    'title' => "Edit {$letter->template->name} | Ajuan Surat",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <div class="mb-1">
              <a href="{{ url()->previous() == route('surat.show', $letter->id) ? route('surat.show', $letter->id) : route('surat.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            <h1 class="page-title">
              Edit Ajuan
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
              {{ $letter->template->name }}
              <x-badge class="fs-4 ms-1" :case="$letter->status" />
            </h2>
          </div>
          @if (Auth::user()->can('create-letter-for-relawan') && $letter->submitted_for_id)
            <div class="card-body">
              <div class="mb-3">
                <label for="penerima" class="form-label required">Penerima Surat</label>
                <select id="penerima" name="submitted_for_id" placeholder="Tuliskan nama"></select>
              </div>
            </div>
          @endif
          @include('hris.surat.templates.' . $letter->template->view)
          <div class="card-body btn-list">
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ url()->previous() == route('surat.show', $letter->id) ? route('surat.show', $letter->id) : route('surat.index') }}" class="btn">Batal</a>
          </div>
        </form>
      </div>
    </div>
  </div>
  @if (Auth::user()->can('create-letter-for-relawan') && $letter->submitted_for_id)
    <script>
      document.addEventListener("DOMContentLoaded", function() {
        if (window.TomSelect) {
          const penerimaOption = new TomSelect('#penerima', {
            valueField: 'id',
            labelField: 'text',
            searchField: 'text',
            maxOptions: 5,
            // minimum query length
            shouldLoad: function(query) {
              return query.length > 2;
            },
            load: function(query, callback) {
              fetch({{ Js::from(route('api.user')) }} + '?q=' + encodeURIComponent(query))
                .then(response => response.json())
                .then(data => callback(data.data || []))
                .catch(() => callback([]));
            }
          });

          const data = @json([
              'id' => $letter->submitted_for_id,
              'text' => $letter->submittedFor->nama,
          ]);
          penerimaOption.addOption(data);
          penerimaOption.addItem(data.id);
        }
      })
    </script>
  @endif
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
@endpush
@push('scripts')
  <script src="{{ asset('static/vendor/tom-select.complete.min.js') }}"></script>
@endpush
