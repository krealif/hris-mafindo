@extends('layouts.dashboard', [
    'title' => 'Dashboard',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Beranda
          </h1>
        </div>
      </div>
    </div>
    <!--Page Body-->
    <div class="page-body">
      <div class="container-xl">
        @if (Auth::user()->registration?->step)
          <div class="card card-mafindo overflow-hidden mb-3">
            <div class="card-header">
              <h2 class="card-title d-flex align-items-center gap-2 mb-0">
                <x-lucide-chevrons-right class="icon" />
                Tahapan Registrasi
              </h2>
            </div>
            <x-registration-step current="{{ Auth::user()->registration?->step }}" :steps="App\Enums\RegistrationBaruStepEnum::steps()" />
          </div>
        @endif
        <div class="row row-deck g-2">
          @role(['relawan-baru', 'relawan-wilayah'])
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('user.profile') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-primary-lt text-white avatar">
                        <x-lucide-user class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Profil Saya</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('surat.letterbox') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-blue-lt text-white avatar">
                        <x-lucide-mailbox class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Kotak Surat</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('kegiatan.indexJoined') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-orange-lt text-white avatar">
                        <x-lucide-calendar-plus class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Kegiatan yang Diikuti</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('kegiatan.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-orange-lt text-white avatar">
                        <x-lucide-calendar-search class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Temukan Kegiatan</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endrole

          @role('admin')
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('registrasi.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-primary-lt text-white avatar">
                        <x-lucide-users class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Permohonan Registrasi</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('surat.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-blue-lt text-white avatar">
                        <x-lucide-file-text class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Permohonan Surat</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('migrasi.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-primary-lt text-white avatar">
                        <x-lucide-user-plus class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Migrasi Data Relawan</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('kegiatan.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-orange-lt text-white avatar">
                        <x-lucide-calendar-days class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Kegiatan</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endrole

          @role('pengurus-wilayah')
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('user.profile') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-primary-lt text-white avatar">
                        <x-lucide-user class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Profil Saya</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('surat.letterbox') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-blue-lt text-white avatar">
                        <x-lucide-mailbox class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Kotak Surat</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('surat.indexWilayah') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-blue-lt text-white avatar">
                        <x-lucide-file-text class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Surat Relawan</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endrole

          <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <a href="{{ route('materi.index') }}" class="card card-mafindo card-sm">
              <div class="card-body">
                <div class="row align-items-center g-3">
                  <div class="col-auto">
                    <span class="bg-green-lt text-white avatar">
                      <x-lucide-book-text class="icon" />
                    </span>
                  </div>
                  <div class="col">
                    <span class="fs-3 fw-bold">Materi</span>
                  </div>
                </div>
              </div>
            </a>
          </div>

          @role('admin')
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('user.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-secondary-lt text-white avatar">
                        <x-lucide-book-text class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Pengguna Sistem</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('wilayah.index') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-secondary-lt text-white avatar">
                        <x-lucide-map class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Wilayah</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endrole

          @role('pengurus-wilayah')
            <div class="col-12 col-sm-6 col-md-4 col-lg-3">
              <a href="{{ route('user.indexWilayah') }}" class="card card-mafindo card-sm">
                <div class="card-body">
                  <div class="row align-items-center g-3">
                    <div class="col-auto">
                      <span class="bg-secondary-lt text-white avatar">
                        <x-lucide-book-text class="icon" />
                      </span>
                    </div>
                    <div class="col">
                      <span class="fs-3 fw-bold">Relawan</span>
                    </div>
                  </div>
                </div>
              </a>
            </div>
          @endrole
        </div>
      </div>
    </div>
  </div>
@endsection
