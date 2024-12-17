@extends('layouts.dashboard', [
    'title' => 'Dashboard',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">
              Beranda
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!--Page Body-->
    <div class="page-body">
      <div class="container-xl">
        @role('relawan-baru')
          <div class="card card-mafindo overflow-hidden">
            <div class="card-header border-bottom-0">
              <h2 class="card-title d-flex align-items-center gap-2 mb-0">
                <x-lucide-chevrons-right class="icon" />
                Tahapan Registrasi
              </h2>
            </div>
            <x-registration-step :data="App\Enums\RegistrationBaruStepEnum::labels()" step="{{ Auth::user()->registration?->step }}" />
          </div>
        @endrole
      </div>
    </div>
  </div>
@endsection
