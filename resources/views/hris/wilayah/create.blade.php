@extends('layouts.dashboard', [
    'title' => 'Tambah Wilayah',
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <a href="{{ route('wilayah.index') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
            <h1 class="page-title">
              Tambah Wilayah
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
          <form class="card card-mafindo" method="POST" action="{{ route('wilayah.store') }}">
            @csrf
            <div class="card-body">
              <div class="mb-3">
                <label for="name" class="form-label required">Nama Wilayah</label>
                <x-form.input name="name" type="text" value="{{ old('name') }}" required />
              </div>
            </div>
            <div class="card-body btn-list">
              <button type="submit" class="btn btn-primary">Simpan</button>
              <a href="{{ route('wilayah.index') }}" class="btn">Batal</a>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
@endsection
