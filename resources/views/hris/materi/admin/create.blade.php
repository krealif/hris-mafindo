@extends('layouts.dashboard', [
    'title' => 'Tambah Materi',
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <a href="{{ route('materi.index') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
            <h1 class="page-title">
              Tambah Materi
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
          <form class="card card-mafindo" method="POST" action="{{ route('materi.store') }}">
            @csrf
            <div class="card-body">
              <div class="mb-3">
                <label for="title" class="form-label required">Judul Materi</label>
                <x-form.input name="title" type="text" placeholder="Tuliskan judul" value="{{ old('title') }}" required />
              </div>
              <div class="mb-2">
                <label for="url" class="form-label required">Tautan Materi</label>
                <x-form.input name="url" type="text" placeholder="Tuliskan tautan materi" value="{{ old('url') }}" required />
              </div>
            </div>
            <div class="card-body btn-list">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('materi.index') }}" class="btn">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
