@extends('layouts.dashboard', [
    'title' => "Edit Sertifikat Milik {$certificate->user->nama} | Kegiatan {$event->name}",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <a href="{{ route('sertifikat.index', $event->id) }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
            <h1 class="page-title">
              Edit Sertifikat
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row g-3">
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
          <form class="card card-mafindo" method="POST" action="{{ route('sertifikat.update', [$event->id, $certificate->id]) }}" enctype="multipart/form-data">
            @csrf
            @method('PATCH')
            <div class="card-body">
              <span class="page-pretitle fs-5">
                Sertifikat
              </span>
              <h2 class="card-title mb-2">{{ $event->name }}</h2>
              <x-badge :case="$event->type" />
            </div>
            <div class="card-body">
              <div class="mb-3">
                <label for="relawan" class="form-label required">Nama Relawan</label>
                <x-form.input name="relawan" type="text" value="{{ $certificate->user->nama }}" disabled />
              </div>
              <div class="mb-2">
                <label for="file" class="form-label required">Upload Ulang Sertifikat</label>
                <x-form.input name="file" type="file" accept=".pdf" required />
                <span class="d-block text-secondary mt-1">pdf (Max: 2 MB)</span>
              </div>
            </div>
            <div class="card-body btn-list">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('sertifikat.index', $event->id) }}" class="btn">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
