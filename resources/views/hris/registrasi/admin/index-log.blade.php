@extends('layouts.dashboard', [
    'title' => 'Log Permohonan Registrasi',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <h1 class="page-title">
              Log Permohonan Registrasi
            </h1>
            <p class="text-secondary m-0 mt-1">
              Lihat dan hapus permohonan registrasi.
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
        <x-dt.datatable search="nama" searchPlaceholder="Nama Relawan" :collection="$registrations">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row gx-4 gy-3">
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
                    'mengisi' => 'Mengisi',
                    'profiling' => 'Profiling',
                    'wawancara' => 'Wawancara',
                    'terhubung' => 'Terhubung',
                    'pelatihan' => 'Pelatihan',
                    'verifikasi' => 'Verifikasi',
                ]" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="status" class="form-label">Status</label>
                <x-form.tom-select name="status" multiple selected="{{ request()->filter['status'] ?? '' }}" :showError=false :options="App\Enums\RegistrationStatusEnum::labels()" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="branch-id" class="form-label">Wilayah</label>
                <x-form.tom-select multiple name="branch_id" :options=$branches :showError=false selected="{{ request()->filter['branch_id'] ?? '' }}" />
              </div>
            </div>
          </x-slot>
          <x-slot:actions>
            <div class="dropdown">
              <a href="#" class="btn btn-icon" data-bs-toggle="dropdown">
                <x-lucide-ellipsis-vertical class="icon" />
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#bulk-delete">Hapus Masal</a>
              </div>
            </div>
          </x-slot>
          <!-- Table Body -->
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead class="table-primary">
              <tr>
                <th>Nama</th>
                <th>Tahapan</th>
                <th>Status</th>
                <th>Tipe</th>
                <th>Email</th>
                <th>Wilayah</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($registrations as $registration)
                <tr x-data="{ id: {{ $registration->id }} }">
                  <td data-label="Nama">
                    @if ($registration->type && (Gate::check('delete', $registration) || $registration->step->value == 'mengisi'))
                      <a href="{{ route('registrasi.show', $registration->id) }}" class="fw-medium">
                        <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                        {{ $registration->user->nama }}
                      </a>
                    @else
                      <span class="fw-medium">
                        <x-lucide-user-cog class="d-none d-lg-inline icon me-1" defer />
                        {{ $registration->user->nama }}
                      </span>
                    @endif
                  </td>
                  <td data-label="Tahapan">
                    <x-badge class="fs-4" :case="$registration->step" />
                  </td>
                  <td data-label="Status">
                    <x-badge class="fs-4" :case="$registration->status" />
                  </td>
                  <td data-label="Tipe">
                    @if ($registration->type)
                      {{ $registration->type?->label() }}
                    @endif
                  </td>
                  <td data-label="Email">{{ $registration->user->email }}</td>
                  <td data-label="Wilayah">{{ $registration->user->branch?->name }}</td>
                  <td data-label="Tanggal">
                    <div>{{ $registration->updated_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-secondary d-block d-md-none d-lg-block">{{ $registration->updated_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      @if ($registration->type && (Gate::check('delete', $registration) || $registration->step->value == 'mengisi'))
                        <a href="{{ route('registrasi.show', $registration->id) }}" class="btn btn-icon">
                          <x-lucide-eye class="icon" defer />
                        </a>
                      @endif
                      @can('delete', $registration)
                        <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn" x-on:click="$dispatch('set-id', { id })">
                          <x-lucide-trash-2 class="icon text-red" defer />
                          Hapus
                        </button>
                      @endcan
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
  <x-modal-delete baseUrl="{{ route('registrasi.index') }}" />
  <div class="modal fade" id="bulk-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form method="POST" action="{{ route('registrasi.bulkDelete') }}" class="modal-content" autocomplete="off">
        <div class="modal-status bg-danger"></div>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Masal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @csrf
          @method('DELETE')
          <div class="mb-3">
            <label for="status-draft" class="form-label">Hapus Permohonan yang berstatus DRAFT?</label>
            <div class="input-group mb-2">
              <span class="input-group-text">
                <input id="status-draft" name="status_draft" class="form-check-input me-2" type="checkbox">
                <label for="lama-draft">Hapus jika lebih dari</label>
              </span>
              <x-form.input name="lama_draft" type="number" value="7" />
              <span class="input-group-text">Hari</span>
              <small class="form-hint mt-1">Centang opsi ini jika Anda ingin menghapus permohonan yang berstatus Draft.</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="status-revisi" class="form-label">Hapus Permohonan yang berstatus REVISI?</label>
            <div class="input-group mb-2">
              <span class="input-group-text">
                <input id="status-revisi" name="status_revisi" class="form-check-input me-2" type="checkbox">
                <label for="lama-revisi">Hapus jika lebih dari</label>
              </span>
              <x-form.input name="lama_revisi" type="number" value="7" />
              <span class="input-group-text">Hari</span>
              <small class="form-hint mt-1">Centang opsi ini jika Anda ingin menghapus permohonan yang berstatus Revisi.</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="status-ditolak" class="form-label">Hapus Permohonan yang DITOLAK?</label>
            <div class="input-group mb-2">
              <span class="input-group-text">
                <input id="status-ditolak" name="status_ditolak" class="form-check-input me-2" type="checkbox">
                <label for="lama-ditolak">Hapus jika lebih dari</label>
              </span>
              <x-form.input name="lama_ditolak" type="number" value="30" />
              <span class="input-group-text">Hari</span>
              <small class="form-hint mt-1">Centang opsi ini jika Anda ingin menghapus permohonan yang berstatus Ditolak.</small>
            </div>
          </div>
          <p class="mb-1"><strong>Disclaimer:</strong></p>
          <ul class="m-0">
            <li>Data yang dihapus akan hilang secara permanen dan tidak dapat dikembalikan.</li>
            <li>Hari yang Anda tentukan merujuk pada berapa lama sejak data terakhir diupdate. Jika data <strong>tidak diperbarui lebih dari hari yang
                ditentukan, maka data akan dihapus.</strong></li>
          </ul>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-danger">Hapus</button>
          <button type="button" class="btn me-auto" data-bs-dismiss="modal">Batal</button>
        </div>
      </form>
    </div>
  </div>
@endsection
