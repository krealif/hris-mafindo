@extends('layouts.dashboard', [
    'title' => 'Surat',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex flex-wrap gap-2 justify-content-between align-items-center">
          <h1 class="page-title">
            Surat
          </h1>
          @can('create-letter')
            <a href="{{ route('surat.template') }}" class="btn btn-primary">
              <x-lucide-plus class="icon" />
              Buat
            </a>
          @endcan
        </div>
      </div>
    </div>
    <!-- Page body -->
    <div class="page-body">
      <div class="container-xl">
        <x-dt.datatable total="{{ $letters->count() }}">
          <table class="table table-vcenter card-table table-striped datatable">
            <thead>
              <tr>
                <th>Surat</th>
                <th></th>
                @can('create-letter-for-relawan')
                  <th>Penerima</th>
                @endcan
                <th>Status</th>
                <th>Timestamp</th>
                <th class="w-1"></th>
              </tr>
            </thead>
            <tbody>
              @foreach ($letters as $letter)
                <tr x-data="{ id: {{ $letter->id }} }">
                  <td>
                    <a href="{{ route('surat.show', $letter->id) }}" class="fw-medium">
                      <x-lucide-file-text class="d-none d-lg-inline icon me-1" defer />
                      {{ $letter->template->name }}
                      @if ($letter->submitted_for_id == Auth::id())
                        <span class="fw-normal">
                          {{ "[dari {$letter->submittedBy->role?->label()}]" }}
                        </span>
                      @endif
                    </a>
                  </td>
                  <td>
                    @if ($letter->submitted_for_id == Auth::id())
                      <x-lucide-square-arrow-down-right class="icon me-1 text-orange" defer />
                      Surat Masuk
                    @else
                      <x-lucide-square-arrow-up-right class="icon me-1 text-blue" defer />
                      Ajuan
                    @endif
                  </td>
                  @can('create-letter-for-relawan')
                    <td>
                      @if ($letter->submitted_for_id && $letter->submitted_for_id != Auth::id())
                        <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                        {{ $letter->submittedFor->nama }}
                      @else
                        <x-lucide-circle-slash-2 class="icon" defer />
                      @endif
                    </td>
                  @endcan
                  <td>
                    <x-badge class="fs-4" :case="$letter->status" />
                  </td>
                  <td>{{ $letter->created_at?->diffForHumans() }}<br>{{ $letter->created_at?->format('d/m/Y H:i') }}</td>
                  <td>
                    <div class="btn-list flex-nowrap justify-content-end">
                      {{-- @if ($letter->status == 'selesai')
                        <a href="{{ route('letter.download', $letter->id) }}" class="btn" target="_blank">
                          <x-lucide-download class="icon text-green" defer />
                          Download
                        </a>
                      @endif --}}
                      @can('update', $letter)
                        <a href="{{ route('surat.edit', $letter->id) }}" class="btn">
                          <x-lucide-pen-line class="icon text-blue" defer />
                          Edit
                        </a>
                      @endcan
                      <a href="{{ route('surat.show', $letter->id) }}" @class([
                          'btn',
                          'btn-icon' => !in_array($letter->status->value, ['diproses', 'ditolak']),
                      ])>
                        <x-lucide-eye class="icon" defer />
                        {{ in_array($letter->status->value, ['diproses', 'ditolak']) ? 'Lihat' : '' }}
                      </a>
                      @can('destroy', $letter)
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
          @if ($letters->hasPages())
            <!-- Pagination -->
            <x-slot:pagination>
              {{ $letters->links() }}
            </x-slot>
          @endif
        </x-dt.datatable>
      </div>
    </div>
  </div>
  <x-modal-delete baseUrl="{{ url('/surat/ajuan') }}" />
@endsection
