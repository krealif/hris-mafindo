@extends('layouts.dashboard', [
    'title' => 'Proses Registrasi',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <h1 class="page-title">
              Proses Registrasi
            </h1>
            <p class="text-muted m-0 mt-1">
              Tindak lanjuti permohonan aktif dari relawan & pengurus.
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <x-dt.datatable search="nama" searchPlaceholder="Nama relawan" :collection="$registrations">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row g-4">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="email" class="form-label">Email</label>
                <x-form.input name="email" type="text" value="{{ request()->filter['email'] ?? '' }}" :showError=false />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="type" class="form-label">Tipe</label>
                <x-form.tom-select name="type" multiple selected="{{ request()->filter['type'] ?? '' }}" :showError=false :options="App\Enums\RegistrationTypeEnum::labels()" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="step" class="form-label">Tahapan</label>
                <x-form.tom-select name="step" multiple selected="{{ request()->filter['step'] ?? '' }}" :showError=false :options="[
                    'profiling' => 'Profiling',
                    'wawancara' => 'Wawancara',
                    'terhubung' => 'Terhubung',
                    'pelatihan' => 'Pelatihan',
                    'verifikasi' => 'Verifikasi',
                ]" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="branch-id" class="form-label">Wilayah</label>
                <x-form.tom-select name="branch_id" :options=$branches selected="{{ request()->filter['branch_id'] ?? '' }}" :showError=false placeholder="" />
              </div>
            </div>
          </x-slot>
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Tahapan</th>
                <th>Tipe</th>
                <th>Email</th>
                <th>Wilayah</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($registrations as $registration)
                <tr>
                  <td data-label="Nama">
                    <a href="{{ route('registrasi.show', $registration->id) }}" class="fw-medium">
                      <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                      {{ $registration->user->nama }}
                    </a>
                  </td>
                  <td data-label="Tahapan">
                    <x-badge class="fs-4" :case="$registration->step" />
                  </td>
                  <td data-label="Tipe">{{ $registration->type?->label() }}</td>
                  <td data-label="Email">{{ $registration->user->email }}</td>
                  <td data-label="Wilayah">{{ $registration->user->branch?->name }}</td>
                  <td data-label="Tanggal">
                    <div>{{ $registration->updated_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-muted d-block d-md-none d-lg-block">{{ $registration->updated_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      <a href="{{ route('registrasi.show', $registration->id) }}" class="btn">
                        <x-lucide-eye class="icon" defer />
                        Lihat
                      </a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </x-dt.datatable>
      </div>
    </div>
  </div>
@endsection
