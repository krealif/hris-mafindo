@extends('layouts.dashboard', [
    'title' => "{$user->nama} | Permohonan Registrasi",
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <a href="{{ url()->previous() == route('registrasi.indexLog') ? route('registrasi.indexLog') : route('registrasi.index') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
            <h1 class="page-title">
              Detail Permohonan
            </h1>
          </div>
          @can('delete', $registration)
            @if (url()->previous() == route('registrasi.indexLog'))
              <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn" x-data="{ id: {{ $registration->id }} }" x-on:click="$dispatch('set-id', { id })">
                <x-lucide-trash-2 class="icon text-red" />
                Hapus
              </button>
            @endif
          @endcan
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div>
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
      </div>
      <div class="row g-3">
        <div class="col-12 col-md-7 col-lg-6 order-1">
          <div class="card card-mafindo">
            <x-registration-step current="{{ $registration->step }}" :steps="App\Enums\RegistrationLamaStepEnum::steps()" />
            <div class="card-body">
              <div class="row g-3">
                <div class="col-12 col-lg-auto">
                  <img src="{{ $user->foto ? Storage::url($user->foto) : asset('static/img/profile-placeholder.png') }}" class="avatar avatar-xl" />
                </div>
                <div class="col">
                  <h2 class="card-title h2 mb-2">{{ $user->nama }}</h2>
                  <h4 class="card-subtitle h3 mb-2 text-dark">
                    {{ $registration->type?->label() }}
                    @if ($user->branch_id)
                      {{ "({$user->branch?->name})" }}
                    @endif
                  </h4>
                  <x-badge class="fs-4" :case="$registration->status" />
                </div>
              </div>
              <div class="datagrid datagrid-h mt-3">
                <x-datagrid-item title="Mendaftar" content="{{ $registration->created_at?->translatedFormat('d F Y / H:i') }}" />
                <x-datagrid-item title="Diperbarui" content="{{ $registration->updated_at?->translatedFormat('d F Y / H:i') }}" />
              </div>
            </div>
            @if (in_array($registration->status->value, ['revisi', 'ditolak']))
              <div class="card-body bg-orange-lt text-dark">
                <h4 class="text-red text-uppercase m-0">Alasan {{ $registration?->status->value }}</h4>
                <p class="mt-2">{{ $registration->message }}</p>
              </div>
            @endif
            @if ($registration->status->value == 'diproses')
              <div class="card-body">
                <ul class="nav nav-pills gap-2" role="tablist">
                  @can('approve', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-selesai" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-check class="icon text-green me-2" />
                        Selesai
                      </a>
                    </li>
                  @endcan
                  @can('requestRevision', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-revisi" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-file-pen-line class="icon text-orange me-2" />
                        Revisi
                      </a>
                    </li>
                  @endcan
                  @can('reject', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-tolak" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-x class="icon text-red me-2" />
                        Tolak
                      </a>
                    </li>
                  @endcan
                </ul>
              </div>
              <div class="tab-content">
                @can('approve', $registration)
                  <div id="tab-selesai" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.approve', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <button class="btn btn-primary" type="submit">Selesaikan</button>
                    </form>
                  </div>
                @endcan
                @can('requestRevision', $registration)
                  <div id="tab-revisi" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.requestRevision', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <div class="mb-4">
                        <label for="message" class="form-label required">Alasan</label>
                        <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan revisi" required />
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
                        <x-form.textarea name="message" rows="5" required />
                      </div>
                      <button class="btn btn-danger" type="submit">Tolak</button>
                    </form>
                  </div>
                @endcan
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-5 col-lg-6 order-3 order-md-2">
          <div class="vstack gap-3">
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Informasi Pengurus</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Email" content="{{ $user->email }}" />
                  <x-datagrid-item title="Wilayah" content="{{ $user->branch?->name }}" />
                  <x-datagrid-item title="Sekretaris 1" content="{{ $user->branch?->staff->sekretaris1 }}" />
                  <x-datagrid-item title="Sekretaris 2" content="{{ $user->branch?->staff->sekretaris2 }}" />
                  <x-datagrid-item title="Bendahara 1" content="{{ $user->branch?->staff->bendahara1 }}" />
                  <x-datagrid-item title="Bendahara 2" content="{{ $user->branch?->staff->bendahara2 }}" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @can('delete', $registration)
    @if (url()->previous() == route('registrasi.indexLog'))
      <x-modal-delete baseUrl="{{ route('registrasi.index') }}" />
    @endif
  @endcan
@endsection
