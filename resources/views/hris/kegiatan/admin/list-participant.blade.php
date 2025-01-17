@extends('layouts.dashboard', [
    'title' => "{$event->name} | Peserta Kegiatan",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div>
          <div class="mb-1">
            @if (in_array(url()->previous(), [route('kegiatan.index'), route('kegiatan.indexJoined'), route('kegiatan.indexHistory')]))
              <a href="{{ url()->previous() }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @elseif ($event->has_joined)
              <a href="{{ route('kegiatan.indexJoined') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @else
              <a href="{{ route('kegiatan.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @endif
          </div>
          <h1 class="page-title">
            Detail Kegiatan
          </h1>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        @include('hris.kegiatan._tabs-detail')
        <div class="row g-3">
          <div class="col-12">
            <div class="card card-mafindo">
              <div class="card-body">
                <h2 class="m-0">
                  Daftar Peserta
                </h2>
                <h3 class="fw-normal mb-3">{{ $event->name }}</h3>
                <div class="d-flex flex-wrap gap-2 align-items-center">
                  <x-lucide-users class="icon" defer />
                  @if ($event->type->value == 'terbatas' && $event->quota)
                    <span>{{ "{$participants->count()} / {$event->quota} Peserta" }}</span>
                  @else
                    <span>{{ "{$participants->count()} Peserta" }}</span>
                  @endif
                </div>
              </div>
            </div>
          </div>
          <div class="col-12">
            <x-dt.datatable search="nama" searchPlaceholder="Nama relawan" :collection="$participants">
              @if ($participants->isNotEmpty())
                <x-slot:actions>
                  <div class="dropdown">
                    <a href="#" class="btn btn-icon" data-bs-toggle="dropdown">
                      <x-lucide-ellipsis-vertical class="icon" />
                    </a>
                    <div class="dropdown-menu">
                      <a href="{{ route('kegiatan.exportParticipant', $event->id) }}" class="dropdown-item">Ekspor CSV</a>
                    </div>
                  </div>
                </x-slot>
              @endif
              <!-- Table Body -->
              <table class="table table-vcenter card-table table-mobile-md datatable">
                <thead class="table-primary">
                  <tr>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>No Relawan</th>
                    <th>Tanggal</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($participants as $participant)
                    <tr x-data="{ id: {{ $participant->id }} }">
                      <td data-label="Nama">
                        <x-lucide-user class="d-none d-lg-inline icon me-1" defer />
                        {{ $participant->nama }}
                      </td>
                      <td data-label="Email">{{ $participant->email }}</td>
                      <td data-label="No Relawan">{{ $participant->no_relawan ?? '-' }}</td>
                      <td data-label="Tanggal">{{ $participant->pivot->created_at?->format('d M Y / H:i') ?? '-' }}</td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </x-dt.datatable>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
