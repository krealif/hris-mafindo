@extends('layouts.dashboard', [
    'title' => 'Registrasi',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <h1 class="page-title">
              Registrasi Relawan
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <x-dt.datatable total="{{ $registrations->count() }}">
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-striped datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Email</th>
                <th>Tipe</th>
                <th>Tahapan</th>
                <th>Status</th>
                <th>Wilayah</th>
                <th>Timestamp</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($registrations as $registration)
                <tr data-id="{{ $registration->id }}">
                  <td>
                    <a href="{{ route('verif.detailRelawan', $registration->id) }}" class="text-decoration-underline text-dark">{{ $registration->user->nama }}</a>
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
                  <td>
                    <x-badge-enum case="{{ $registration->status }}" :enumClass="App\Enums\RegistrationStatusEnum::class" />
                  </td>
                  <td>{{ $registration->user->branch?->nama }}</td>
                  <td>{{ $registration->updated_at }}</td>
                  <td>
                    <div class="btn-list flex-nowrap justify-content-end">
                      <a href="{{ route('verif.detailRelawan', $registration->id) }}" class="btn">
                        <x-lucide-eye class="icon" />
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
