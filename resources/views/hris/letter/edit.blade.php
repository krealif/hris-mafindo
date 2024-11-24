@extends('layouts.dashboard', [
  'title' => 'Detail Surat'
])

@section('content')
<div class="page-wrapper">
  <div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <div class="mb-1">
            <x-breadcrumb>
              <x-breadcrumb-item label="Persuratan" route="letter.index" />
            </x-breadcrumb>
          </div>
          <h1 class="page-title">
            Edit Surat
          </h1>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="page-body">
  <div class="container-xl">
    <div class="col-12 col-md-10 col-lg-6">
      <form class="card" method="POST">
        @csrf
        <div class="card-header">
          <h2 class="card-title badge bg-dark text-white">{{ $letter->letterTemplate->name }}</h2>
        </div>
        @include('hris.letter.templates.' . $letter->letterTemplate->view)
        <div class="card-footer d-flex justify-content-end">
          <a href="{{ route('letter.index') }}" class="btn btn-link me-2">Batal</a>
          <button type="submit" class="btn btn-primary">Simpan</button>
        </div>
      </form>
    </div>
  </div>
</div>
@endsection
