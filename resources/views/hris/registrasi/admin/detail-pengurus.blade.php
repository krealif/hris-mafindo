@extends('layouts.dashboard', [
    'title' => $registration->user->nama . ' | Detail Ajuan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex gap-2 justify-content-between align-items-center">
          <div class="col">
            <div class="mb-1">
              <x-breadcrumb>
                @if (url()->previous() == route('registrasi.history'))
                  <x-breadcrumb-item label="Histori" route="registrasi.history" />
                @else
                  <x-breadcrumb-item label="Ajuan" route="registrasi.index" />
                @endif
              </x-breadcrumb>
            </div>
            <h1 class="page-title">
              Detail Ajuan
            </h1>
          </div>
          @can('destroy', $registration)
            <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn" x-data="{ id: {{ $registration->id }} }" x-on:click="$dispatch('set-id', { id })">
              <x-lucide-trash-2 class="icon text-red" />
              Hapus
            </button>
          @endcan
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row g-3">
        <div class="col-12">
          <div class="card card-mafindo overflow-hidden border-top-0">
            <x-registration-step :data="App\Enums\RegistrationLamaStepEnum::labels()" step="{{ $registration?->step }}" />
            <div class="card-body border-top">
              <h2 class="card-title h2 mb-2">{{ $user->nama }}</h2>
              <h4 class="card-subtitle h3 mb-2 text-muted">{{ $user->branch?->nama }}</h4>
              <x-badge-enum class="fs-4 me-1" case="{{ $registration->type }}" :enumClass="App\Enums\RegistrationTypeEnum::class" />
              <x-badge-enum class="fs-4" case="{{ $registration->status }}" :enumClass="App\Enums\RegistrationStatusEnum::class" />
            </div>
            @if ($registration?->status == 'revisi' && $registration->message)
              <div class="card-body border-top">
                <h4 class="fs-3 text-red">REVISI</h4>
                <p>{{ $registration->message }}</p>
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="vstack gap-2">
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Informasi Pengurus</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Nama Koordinator" content="{{ $user->nama }}" />
                  <x-datagrid-item title="Email" content="{{ $user->email }}" />
                  <x-datagrid-item title="Wilayah" content="{{ $user->branch?->nama }}" />
                  <x-datagrid-item title="Sekretaris 1" content="{{ $user->branch?->pengurus->sekretaris1 }}" />
                  <x-datagrid-item title="Sekretaris 2" content="{{ $user->branch?->pengurus->sekretaris2 }}" />
                  <x-datagrid-item title="Bendahara 1" content="{{ $user->branch?->pengurus->bendahara1 }}" />
                  <x-datagrid-item title="Bendahara 2" content="{{ $user->branch?->pengurus->bendahara2 }}" />
                </div>
              </div>
            </div>
          </div>
        </div>
        @if ($registration->status == 'diproses')
          <div class="col-12 col-md-6">
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
            <div class="card card-mafindo sticky-top">
              <div class="card-header">
                <h3 class="card-title">Aksi</h3>
              </div>
              <div class="card-body">
                <ul class="nav nav-pills gap-2" role="tablist">
                  @can('finish', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-selesai" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-check class="icon text-green me-2" />
                        Selesai
                      </a>
                    </li>
                  @endcan
                  @can('requestRevision', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-revisi" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-file-pen-line class="icon text-orange me-2" />
                        Revisi
                      </a>
                    </li>
                  @endcan
                  @can('reject', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-tolak" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-x class="icon text-red me-2" />
                        Tolak
                      </a>
                    </li>
                  @endcan
                </ul>
              </div>
              <div class="tab-content">
                @can('finish', $registration)
                  <div id="tab-selesai" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.finish', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <button class="btn btn-primary" type="submit">Selesaikan Registrasi</button>
                    </form>
                  </div>
                @endcan
                @can('requestRevision', $registration)
                  <div id="tab-revisi" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.revisi', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <div class="mb-4">
                        <label for="message" class="form-label required">Alasan</label>
                        <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan revisi" :showError=false required />
                      </div>
                      <button class="btn btn-primary" type="submit">Kirim</button>
                    </form>
                  </div>
                @endcan
                @can('reject', $registration)
                  <div id="tab-tolak" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.reject', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <div class="mb-4">
                        <label for="message" class="form-label required">Pesan</label>
                        <x-form.textarea name="message" rows="5" :showError=false required />
                      </div>
                      <button class="btn btn-danger" type="submit">Tolak Registrasi</button>
                    </form>
                  </div>
                @endcan
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script></script>
@endpush
