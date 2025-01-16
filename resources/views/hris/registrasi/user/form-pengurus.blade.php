@extends('layouts.unverified', [
    'title' => 'Registrasi Pengurus',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">Registrasi Pengurus</h1>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        <div class="row g-3">
          <div class="col-12">
            @if (flash()->message)
              <x-alert type="{{ flash()->class }}">
                {{ flash()->message }}
              </x-alert>
            @endif
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
            <div class="card card-mafindo overflow-hidden">
              <div class="card-header">
                <h2 class="card-title d-flex align-items-center gap-2 mb-0">
                  <x-lucide-chevrons-right class="icon" />
                  Tahapan
                  <x-badge class="fs-4 me-1" :case="$registration->status" />
                </h2>
              </div>
              <x-registration-step current="{{ $registration?->step }}" :steps="App\Enums\RegistrationLamaStepEnum::steps()" />
              @if (in_array($registration?->status->value, ['revisi', 'ditolak']))
                <div class="card-body bg-orange-lt text-dark">
                  <h4 class="text-red text-uppercase m-0">Alasan {{ $registration?->status->value }}</h4>
                  @if ($registration->status->value == 'revisi')
                    <p class="m-0">Mohon untuk memperbaiki data sesuai dengan arahan berikut</p>
                    <hr class="m-0 mt-2">
                  @endif
                  <p class="mt-2">{{ $registration->message }}</p>
                </div>
              @endif
            </div>
          </div>
          @can('create', [App\Models\Registration::class, $type])
            <div class="col-12 col-md-3 mb-3 mb-md-0">
              <div class="card card-mafindo sticky-top">
                <div class="card-header">
                  <h3 class="card-title d-flex align-items-center gap-2">
                    <x-lucide-list class="icon" />
                    Daftar Isi
                  </h3>
                </div>
                <nav class="list-group list-group-flush">
                  <a class="list-group-item list-group-item-action toc-item" href="#informasi-pribadi">
                    <x-lucide-chevron-right class="icon" defer />
                    Informasi Wilayah
                  </a>
                </nav>
              </div>
            </div>
            <div class="col-12 col-md-9">
              <form method="POST" action="{{ route('registrasi.store', $type->value) }}" class="vstack gap-3" x-data="{ isDraft: false }" x-bind:novalidate="isDraft"
                enctype="multipart/form-data" autocomplete="off">
                @csrf
                <div id="informasi-pribadi" class="card card-mafindo">
                  <div class="card-header">
                    <h2 class="card-title">Informasi Wilayah</h2>
                  </div>
                  <div class="card-body">
                    <div class="row mb-3">
                      <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label for="coordinatorName" class="form-label required">Koordinator</label>
                        <x-form.input name="coordinatorName" type="text" value="{{ old('coordinatorName', $user->nama) }}" required />
                      </div>
                      <div class="col-12 col-md-6">
                        <label for="branch" class="form-label required">Wilayah</label>
                        <x-form.tom-select id="branch" name="branch_id" :options=$branches selected="{{ old('branch', $user->branch_id) }}" placeholder="" />
                      </div>
                    </div>
                    @if ($errors->has('staff.*'))
                      <x-alert class="alert-danger">
                        <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                        <ul class="mt-2 mb-0" style="margin-left: -1rem">
                          @foreach ($errors->get('staff.*') as $e)
                            @foreach ($e as $error)
                              <li>{{ $error }}</li>
                            @endforeach
                          @endforeach
                        </ul>
                      </x-alert>
                    @endif
                    <div class="row mb-3">
                      <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label for="sekretaris1" class="form-label">Sekretaris 1</label>
                        <x-form.input id="sekretaris1" name="staff[sekretaris1]" type="text" value="{{ old('staff.sekretaris1', $user->branch?->staff->sekretaris1) }}" />
                      </div>
                      <div class="col-12 col-md-6">
                        <label for="sekretaris2" class="form-label">Sekretaris 2</label>
                        <x-form.input id="sekretaris2" name="staff[sekretaris2]" type="text" value="{{ old('staff.sekretaris2', $user->branch?->staff->sekretaris2 ?? '') }}" />
                      </div>
                    </div>
                    <div class="row mb-3">
                      <div class="col-12 col-md-6 mb-3 mb-md-0">
                        <label for="bendahara1" class="form-label">Bendahara 1</label>
                        <x-form.input id="bendahara1" name="staff[bendahara1]" type="text" value="{{ old('staff.bendahara1', $user->branch?->staff->bendahara1) }}" />
                      </div>
                      <div class="col-12 col-md-6">
                        <label for="bendahara2" class="form-label">Bendahara 2</label>
                        <x-form.input id="bendahara2" name="staff[bendahara2]" type="text" value="{{ old('staff.bendahara2', $user->branch?->staff->bendahara2) }}" />
                      </div>
                    </div>
                  </div>
                </div>

                <div class="card bg-primary-lt shadow position-sticky bottom-0 z-3">
                  <input type="hidden" name="_isDraft" x-model="isDraft">
                  <div class="card-body btn-list">
                    <button class="btn btn-primary">
                      <x-lucide-send class="icon" />
                      Ajukan
                    </button>
                    <button class="btn btn-secondary" x-on:click="isDraft = true">
                      <x-lucide-save class="icon" />
                      Simpan Sementara
                    </button>
                  </div>
                </div>
              </form>
            </div>
          @endcan
        </div>
      </div>
    </div>
  </div>
@endsection
