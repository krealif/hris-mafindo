@extends('layouts.dashboard', [
    'title' => "Wilayah {$branch->name}",
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <div class="mb-1">
              <a href="{{ route('wilayah.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            <h1 class="page-title">
              Wilayah {{ $branch->name }}
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-deck g-2 mb-2">
          <div class="col-12 col-sm">
            <div class="card card-mafindo card-sm">
              <div class="card-body">
                <div class="fw-bold text-secondary">
                  Total Pengguna
                </div>
                <div class="fs-2">
                  {{ $roleCounts->sum('count') }}
                </div>
              </div>
            </div>
          </div>
          @foreach ($roleCounts as $roleCount)
            @unless ($roleCount->role_name == 'pengurus-wilayah')
              <div class="col">
                <div class="card card-mafindo card-sm">
                  <div class="card-body">
                    <div class="fw-bold text-secondary">
                      {{ ucwords(str_replace('-', ' ', $roleCount->role_name)) }}
                    </div>
                    <div class="fs-2">
                      {{ $roleCount->count }}
                    </div>
                  </div>
                </div>
              </div>
            @endunless
          @endforeach
        </div>
        <div class="card mb-3">
          <div class="card-body">
            <div class="datagrid">
              <x-datagrid-item title="Sekretaris 1" content="{{ $branch->staff->sekretaris1 }}" />
              <x-datagrid-item title="Sekretaris 2" content="{{ $branch->staff->sekretaris2 }}" />
              <x-datagrid-item title="Bendahara 1" content="{{ $branch->staff->bendahara1 }}" />
              <x-datagrid-item title="Bendahara 2" content="{{ $branch->staff->bendahara2 }}" />
            </div>
          </div>
        </div>
        <x-dt.datatable :collection="$users">
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Role</th>
                <th>Email</th>
                <th>No. Relawan</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($users as $user)
                <tr x-data="{ id: {{ $user->id }} }">
                  <td data-label="Nama">
                    <a href="{{ route('user.profile', $user->id) }}" class="fw-medium">
                      <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                      {{ $user->nama }}
                    </a>
                  </td>
                  <td data-label="Role">
                    <x-badge :case="$user->role" />
                  </td>
                  <td data-label="Email">{{ $user->email }}</td>
                  <td data-label="No Relawan">
                    @if ($user->no_relawan)
                      {{ $user->no_relawan }}
                    @else
                      <x-lucide-circle-slash-2 class="icon" defer />
                    @endif
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
