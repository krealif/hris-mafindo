@extends('layouts.dashboard', [
    'title' => $registration->user->nama . ' | Detail Pengurus',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <div class="mb-1">
              <x-breadcrumb>
                <x-breadcrumb-item label="Registrasi" route="verif.indexPengurus" />
              </x-breadcrumb>
            </div>
            <h1 class="page-title">
              Detail Pengurus
            </h1>
          </div>
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
                  <x-datagrid-item title="Sekretaris 1" content="{{ $user->branch?->nama }}" />
                  <x-datagrid-item title="Sekretaris 2" content="{{ $user->branch?->nama }}" />
                  <x-datagrid-item title="Wilayah" content="{{ $user->branch?->nama }}" />
                  <x-datagrid-item title="Wilayah" content="{{ $user->branch?->nama }}" />
                </div>
              </div>
            </div>
          </div>
        </div>
        @if ($registration->status == 'diproses')
          <div class="col-12 col-md-6">
            @if ($errors->any())
              <x-alert class="alert-danger">
                <div>Error! Terjadi kesalahan saat mengirimkan form. Tolong periksa kembali data yang Anda masukkan.</div>
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
                  @if ($registration->step == 'verifikasi')
                    <li class="nav-item" role="presentation">
                      <a href="#tab-selesai" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-check class="icon text-green me-2" />
                        Selesai
                      </a>
                    </li>
                  @endif
                  @if ($registration->step == 'verifikasi')
                    <li class="nav-item" role="presentation">
                      <a href="#tab-revisi" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-file-pen-line class="icon text-orange me-2" />
                        Revisi
                      </a>
                    </li>
                  @endif
                </ul>
              </div>
              <div class="tab-content">
                @if ($registration->step == 'verifikasi')
                  <div id="tab-selesai" class="tab-pane">
                    <form method="POST" action="{{ route('verif.finish', $registration->id) }}" class="card-body border-top">
                      @csrf
                      <button class="btn btn-primary" type="submit">Selesaikan Registrasi</button>
                    </form>
                  </div>
                @endif
                @if ($registration->step == 'verifikasi')
                  <div id="tab-revisi" class="tab-pane">
                    <form method="POST" action="{{ route('verif.revisi', $registration->id) }}" class="card-body border-top">
                      @csrf
                      <div class="mb-4">
                        <label for="message" class="form-label required">Alasan</label>
                        <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan revisi" :showError=false required />
                      </div>
                      <button class="btn btn-primary" type="submit">Kirim</button>
                    </form>
                  </div>
                @endif
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
