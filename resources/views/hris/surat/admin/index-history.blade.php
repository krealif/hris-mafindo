@extends('layouts.dashboard', [
    'title' => 'Histori Permohonan Surat',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <h1 class="page-title">
              Histori Permohonan Surat
            </h1>
            <p class="text-muted m-0 mt-1">
              Lihat seluruh permohonan surat yang telah diproses.
            </p>
          </div>
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <x-dt.datatable search="title" searchPlaceholder="Cari judul surat" :collection="$letters">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row gx-4 gy-3">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="type" class="form-label">Tipe</label>
                <x-form.select name="type" selected="{{ request()->filter['type'] ?? '' }}" :showError=false :options="[
                    '' => 'Semua',
                    1 => 'Buat',
                    0 => 'Permohonan',
                ]" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="status" class="form-label">Status</label>
                <x-form.tom-select name="status" multiple selected="{{ request()->filter['status'] ?? '' }}" :showError=false :options="App\Enums\LetterStatusEnum::labels()" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="created_by" class="form-label">Pengirim</label>
                <x-dt.user-filter name="created_by" selected="{{ request()->filter['created_by'] ?? '' }}" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="recipient" class="form-label">Tujuan</label>
                <x-dt.user-filter name="recipient" selected="{{ request()->filter['recipient'] ?? '' }}" />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="updated_at" class="form-label">Tanggal</label>
                <x-dt.date-filter name="updated_at" value="{{ request()->filter['updated_at'] ?? '' }}" />
              </div>
            </div>
          </x-slot>
          <x-slot:actions>
            <div class="dropdown">
              <a href="#" class="btn btn-icon" data-bs-toggle="dropdown">
                <x-lucide-ellipsis-vertical class="icon" />
              </a>
              <div class="dropdown-menu">
                <a class="dropdown-item" data-bs-toggle="modal" data-bs-target="#bulk-delete">Bersihkan</a>
              </div>
            </div>
          </x-slot>
          <table class="table table-vcenter card-table table-mobile-md datatable">
            <thead>
              <tr>
                <th>Judul</th>
                <th>Tipe</th>
                <th>Pengirim</th>
                <th>Tujuan</th>
                <th>Status</th>
                <th>Tanggal</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($letters as $letter)
                <tr x-data="{ id: {{ $letter->id }} }">
                  <td data-label="Judul" class="letter-title">
                    <a href="{{ route('surat.show', $letter->id) }}" class="fw-medium">
                      <x-lucide-file-text class="d-none d-lg-inline icon me-1" defer />
                      {{ $letter->title }}
                    </a>
                  </td>
                  <td data-label="Tipe">
                    @if ($letter->createdBy->hasRole('admin'))
                      <x-lucide-square-arrow-right class="icon me-1 text-pink" defer />
                      <strong class="fw-medium">BUAT PERMOHONAN</strong>
                    @else
                      <x-lucide-square-arrow-up-right class="icon me-1 text-blue" defer />
                      <strong class="fw-medium">PERMOHONAN</strong> {{ $letter->createdBy->role?->label() }}
                    @endif
                  </td>
                  <td data-label="Pengirim">
                    @if ($letter->created_by != Auth::id())
                      {{ $letter->createdBy->nama }}
                    @else
                      <x-lucide-circle-slash-2 class="icon" defer />
                    @endif
                  </td>
                  <td data-label="Tujuan">
                    @if ($letter->recipients->isNotEmpty())
                      <div style="max-width: 240px">
                        @foreach ($letter->recipients as $recipient)
                          @if ($loop->last)
                            {{ $recipient->nama }}
                          @else
                            {{ $recipient->nama }} |
                          @endif
                        @endforeach
                      </div>
                    @else
                      <x-lucide-circle-slash-2 class="icon" defer />
                    @endif
                  </td>
                  <td data-label="Status">
                    <x-badge class="fs-4" :case="$letter->status" />
                  </td>
                  <td data-label="Tanggal">
                    <div>{{ $letter->updated_at?->translatedFormat('d M Y / H:i') }}</div>
                    <div class="text-muted d-block d-md-none d-lg-block">{{ $letter->updated_at?->diffForHumans() }}</div>
                  </td>
                  <td data-label="Aksi">
                    <div class="btn-list flex-nowrap justify-content-md-end">
                      @if ($letter->status->value == 'selesai')
                        <a href="{{ route('surat.download', $letter->id) }}" class="btn btn-icon" target="_blank">
                          <x-lucide-download class="icon text-green" defer />
                        </a>
                      @endif
                      <a href="{{ route('surat.show', $letter->id) }}" class="btn">
                        <x-lucide-eye class="icon" defer />
                        Lihat
                      </a>
                      @can('delete', $letter)
                        <button class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-on:click="$dispatch('set-id', { id })">
                          <x-lucide-trash-2 class="icon text-red" defer />
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
  <x-modal-delete baseUrl="{{ route('surat.index') }}" />
  <div class="modal fade" id="bulk-delete" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered" role="document">
      <form method="POST" action="{{ route('surat.bulkDelete') }}" class="modal-content" autocomplete="off">
        <div class="modal-status bg-danger"></div>
        <div class="modal-header">
          <h5 class="modal-title">Hapus Masal</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          @csrf
          @method('DELETE')
          <div class="mb-3">
            <label for="status-revisi" class="form-label">Hapus Surat dengan status REVISI?</label>
            <div class="input-group mb-2">
              <span class="input-group-text">
                <input id="status-revisi" name="status_revisi" class="form-check-input me-2" type="checkbox">
                <label for="lama-revisi">Hapus jika lebih dari</label>
              </span>
              <x-form.input name="lama_revisi" type="number" value="12" />
              <span class="input-group-text">Bulan</span>
              <small class="form-hint mt-1">Centang opsi ini jika Anda ingin menghapus data surat yang statusnya masih revisi dan sudah lama tidak diperbaiki.</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="status-ditolak" class="form-label">Hapus Surat yang DITOLAK?</label>
            <div class="input-group mb-2">
              <span class="input-group-text">
                <input id="status-ditolak" name="status_ditolak" class="form-check-input me-2" type="checkbox">
                <label for="lama-ditolak">Hapus jika lebih dari</label>
              </span>
              <x-form.input name="lama_ditolak" type="number" value="12" />
              <span class="input-group-text">Bulan</span>
              <small class="form-hint mt-1">Centang opsi ini jika Anda ingin menghapus data surat yang statusnya ditolak.</small>
            </div>
          </div>
          <div class="mb-3">
            <label for="status-selesai" class="form-label">Hapus Surat yang telah SELESAI?</label>
            <div class="input-group mb-2">
              <span class="input-group-text">
                <input id="status-selesai" name="status_selesai" class="form-check-input me-2" type="checkbox">
                <label for="lama-selesai">Hapus jika lebih dari</label>
              </span>
              <x-form.input name="lama_selesai" type="number" value="12" />
              <span class="input-group-text">Bulan</span>
              <small class="form-hint mt-1">Centang opsi ini jika Anda ingin menghapus data surat yang statusnya telah selesai.</small>
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
