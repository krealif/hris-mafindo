@extends('layouts.dashboard', [
    'title' => "{$user->nama} | Ajuan Registrasi",
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <div>
            <div class="mb-1">
              <a href="{{ url()->previous() == route('registrasi.indexLog') ? route('registrasi.indexLog') : route('registrasi.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            <h1 class="page-title">
              Detail Ajuan
            </h1>
          </div>
          @can('destroy', $registration)
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
      <div class="row g-3">
        <div class="col-12 hidden-if-empty order-first">
          @if (flash()->message)
            <x-alert type="{{ flash()->class }}" class="m-0">
              {{ flash()->message }}
            </x-alert>
          @endif
          @if ($errors->any())
            <x-alert class="alert-danger m-0">
              <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
              <ul class="mt-2 mb-0" style="margin-left: -1rem">
                @foreach ($errors->all() as $error)
                  <li>{{ $error }}</li>
                @endforeach
              </ul>
            </x-alert>
          @endif
        </div>
        <div class="col-12 col-md-7 col-lg-6 order-1">
          <div class="card card-mafindo overflow-hidden border-top-0">
            <x-registration-step current="{{ $registration->step }}" :steps="App\Enums\RegistrationLamaStepEnum::steps()" />
            <div class="card-body border-top">
              <h2 class="card-title h2 mb-2">{{ $user->nama }}</h2>
              <h4 class="card-subtitle h3 mb-2 text-muted">{{ $user->branch?->nama }}</h4>
              <div class="d-flex flex-wrap gap-2">
                <x-badge class="fs-4" :case="$registration->type" />
                <x-badge class="fs-4" :case="$registration->status" />
              </div>
              @if (in_array($registration->status->value, ['revisi', 'ditolak']))
                <div class="card card-body mt-3">
                  <h4 class="text-red text-uppercase">Alasan</h4>
                  <p>{{ $registration->message }}</p>
                </div>
              @endif
              <div class="mt-3">
                <table class="datagrid">
                  <tr>
                    <th class="datagrid-title">Mendaftar</th>
                    <td>{{ $registration->created_at?->format('d/m/Y H:i') }}</td>
                  </tr>
                  <tr>
                    <th class="datagrid-title">Diperbarui</th>
                    <td>{{ $registration->updated_at?->format('d/m/Y H:i') }}</td>
                  </tr>
                </table>
              </div>
            </div>
            @if ($registration->status->value == 'diproses')
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
                  <x-datagrid-item title="Koordinator" content="{{ $user->nama }}" />
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
      </div>
    </div>
  </div>
  @can('destroy', $registration)
    @if (url()->previous() == route('registrasi.indexLog'))
      <x-modal-delete baseUrl="{{ route('registrasi.index') }}" />
    @endif
  @endcan
@endsection
