@extends('layouts.dashboard', [
    'title' => 'Registrasi Relawan & Pengurus',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">
              Registrasi Relawan & Pengurus
            </h1>
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
        <x-dt.datatable search="user.nama" searchPlaceholder="Pencarian nama" total="{{ $registrations->count() }}">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row g-4">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="email" class="form-label">Email</label>
                <x-form.input id="email" name="user.email" type="text" value="{{ request()->filter['user.email'] ?? '' }}" :showError=false />
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
                <label for="branch" class="form-label">Wilayah</label>
                <x-form.tom-select id="branch" name="user.branch_id" :options=$branches selected="{{ request()->filter['user.branch_id'] ?? '' }}" :showError=false>
                  <option selected></option>
                </x-form.tom-select>
              </div>
            </div>
          </x-slot>
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-striped datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Tipe</th>
                <th>Tahapan</th>
                <th>Wilayah</th>
                <th>Timestamp</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($registrations as $registration)
                <tr>
                  <td>
                    <a href="{{ route('verif.show', $registration->id) }}" class="text-decoration-underline text-dark">{{ $registration->user->nama }}</a>
                  </td>
                  <td>{{ $registration->user->email }}</td>
                  <td>
                    <x-badge-enum case="{{ $registration->type }}" :enumClass="App\Enums\RegistrationTypeEnum::class" />
                  </td>
                  <td>
                    @if ($registration->type == 'relawan-baru')
                      <x-badge-enum case="{{ $registration->step }}" :enumClass="App\Enums\RegistrationBaruStepEnum::class" />
                    @else
                      <x-badge-enum case="{{ $registration->step }}" :enumClass="App\Enums\RegistrationLamaStepEnum::class" />
                    @endif
                  </td>
                  <td>{{ $registration->user->branch?->nama }}</td>
                  <td>{{ $registration->updated_at?->format('d/m/Y H:i') }}</td>
                  <td>
                    <div class="btn-list flex-nowrap">
                      <a href="{{ route('verif.show', $registration->id) }}" class="btn">
                        <x-lucide-eye class="icon" defer />
                        Lihat
                      </a>
                    </div>
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
          @if ($registrations->hasPages())
            <!-- Pagination -->
            <x-slot:pagination>
              {{ $registrations->links() }}
            </x-slot>
          @endif
        </x-dt.datatable>
      </div>
    </div>
  </div>
@endsection
