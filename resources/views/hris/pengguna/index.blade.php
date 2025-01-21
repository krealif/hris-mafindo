@extends('layouts.dashboard', [
    'title' => 'Pengguna',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Pengguna
          </h1>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        <div class="row row-deck row-cards mb-3">
          <div class="col-12 col-sm-8 col-lg-4">
            <div class="card card-mafindo card-sm">
              <div class="card-body">
                <div class="fw-bold text-secondary">
                  Total Semua
                </div>
                <div class="fs-2">
                  {{ $roleCounts->sum('count') }}
                </div>
              </div>
            </div>
          </div>
          @foreach ($roleCounts as $roleCount)
            <div class="col-6 col-sm-4 col-lg-2">
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
          @endforeach
        </div>
        <x-dt.datatable search="nama" searchPlaceholder="Nama Pengguna" :collection="$users">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row gx-4 gy-3">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="role" class="form-label">Role</label>
                <x-form.tom-select name="role" multiple :options="App\Enums\RoleEnum::labels()" selected="{{ request()->filter['role'] ?? '' }}" :showError=false placeholder="" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="email" class="form-label">Email</label>
                <x-form.input name="email" type="text" :showError=false value="{{ request()->filter['email'] ?? '' }}" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="branch-id" class="form-label">Wilayah</label>
                <x-form.tom-select name="branch_id" :options=$branches selected="{{ request()->filter['branch_id'] ?? '' }}" :showError=false placeholder="" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="no_relawan" class="form-label">No Relawan</label>
                <x-form.input name="no_relawan" type="text" :showError=false value="{{ request()->filter['no_relawan'] ?? '' }}" />
              </div>
            </div>
          </x-slot>
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Role</th>
                <th>Email</th>
                <th>Wilayah</th>
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
                  <td data-label="Wilayah">
                    @if ($user->branch_id)
                      {{ $user->branch?->name }}
                    @else
                      <x-lucide-circle-slash-2 class="icon" defer />
                    @endif
                  </td>
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
