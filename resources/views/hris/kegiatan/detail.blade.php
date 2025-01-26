@extends('layouts.dashboard', [
    'title' => "{$event->name} | Kegiatan",
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div>
          @if (in_array(url()->previous(), [route('kegiatan.index'), route('kegiatan.indexJoined'), route('kegiatan.indexArchive')]))
            <a href="{{ url()->previous() }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
          @elseif ($event->status->value == 'selesai')
            <a href="{{ route('kegiatan.indexArchive') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
          @elseif ($event->has_joined)
            <a href="{{ route('kegiatan.indexJoined') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
          @else
            <a href="{{ route('kegiatan.index') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
          @endif
          <h1 class="page-title">
            Detail Kegiatan
          </h1>
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        @haspermission('create-event')
          @include('hris.kegiatan._tabs-detail')
        @endhaspermission
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <div class="row g-3">
          <div class="col-12 col-md-5 col-lg-4">
            <div class="card card-mafindo">
              <img class="ratio ratio-1x1 rounded" src="{{ Storage::url($event->cover) }}" />
              @if ($event->has_started && $event->status->value == 'aktif')
                <div class="ribbon ribbon-start bg-red fs-4">
                  Saat Ini
                </div>
              @endif
            </div>
          </div>
          <div class="col-12 col-md-7 col-lg-8">
            <div class="card card-mafindo">
              <div class="card-body">
                <div class="hstack gap-2 mb-2">
                  @if ($event->status->value == 'selesai')
                    <x-badge class="fs-4" :case="$event->status" />
                  @endif
                  <x-badge class="fs-4" :case="$event->type" />
                </div>
                <h2>{{ $event->name }}</h2>
                <div class="vstack gap-1">
                  <div class="d-flex flex-wrap gap-2 align-items-center">
                    <x-lucide-users class="icon" defer />
                    @if ($event->type->value == 'terbatas' && $event->quota)
                      <span>{{ "{$event->participants_count} / {$event->quota} Peserta" }}</span>
                    @else
                      <span>{{ "{$event->participants_count} Peserta" }}</span>
                    @endif
                  </div>
                  <div class="d-flex flex-wrap gap-2 align-items-center">
                    <x-lucide-calendar-days class="icon" />
                    <span>{{ "{$event->start_date?->translatedFormat('d F Y')}, Pukul {$event->start_date?->translatedFormat('H:i')}" }}</span>
                  </div>
                </div>
                @if ($event->has_joined && $event->status->value == 'selesai')
                  <div class="mt-3">
                    <span class="badge bg-primary-lt fs-4 text-uppercase d-inline-flex gap-2">
                      <x-lucide-check class="icon" />
                      Mengikuti
                    </span>
                  </div>
                @endif
              </div>
              @haspermission('join-event')
                {{-- Aksi yang dilakukan oleh Relawan --}}
                @can('join', $event)
                  <div class="card-body">
                    <div class="btn-list">
                      @if ($event->has_joined)
                        <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal-event">
                          <x-lucide-square-check-big class="icon" />
                          Mengikuti
                        </button>
                        @if ($event->meeting_url)
                          <a href="{{ $event->meeting_url }}" class="btn" target="_blank">
                            <x-lucide-link class="icon" />
                            Link Meeting
                          </a>
                        @endif
                      @else
                        @if ($event->type->value == 'terbatas' && $event->participants_count >= $event->quota)
                          <button type="button" class="btn btn-primary" disabled>
                            <x-lucide-ban class="icon" />
                            Penuh
                          </button>
                        @else
                          <form method="POST" action="{{ route('kegiatan.join', $event->id) }}">
                            @csrf
                            <button type="submit" class="btn btn-primary">
                              <x-lucide-calendar-plus class="icon" />
                              Ikuti
                            </button>
                          </form>
                        @endif
                      @endif
                    </div>
                  </div>
                @elseif ($event->has_joined && ($event->has_certificate || $event->recording_url))
                  <div class="card-body">
                    <div class="btn-list">
                      @if ($event->has_certificate)
                        <a href="{{ route('sertifikat.downloadForRelawan', $event->id) }}" class="btn" target="_blank">
                          <x-lucide-file-badge class="icon text-orange" />
                          Download Sertifikat
                        </a>
                      @endif
                      @if ($event->recording_url)
                        <a href="{{ $event->recording_url }}" class="btn" target="_blank">
                          <x-lucide-square-play class="icon text-red" />
                          Link Rekaman
                        </a>
                      @endif
                    </div>
                  </div>
                @elseif ($event->type->value == 'terbuka' && $event->recording_url)
                  <div class="card-body">
                    <div class="btn-list">
                      <a href="{{ $event->recording_url }}" class="btn" target="_blank">
                        <x-lucide-square-play class="icon text-red" />
                        Link Rekaman
                      </a>
                    </div>
                  </div>
                @endcan
              @else
                @canany(['finish', 'update', 'delete'], $event)
                  {{-- Aksi yang dilakukan oleh Admin --}}
                  <div class="card-body">
                    <div class="btn-list">
                      @can('finish', $event)
                        <form method="POST" action="{{ route('kegiatan.finish', $event->id) }}">
                          @csrf
                          @method('PATCH')
                          <button href="{{ route('kegiatan.finish', $event->id) }}" class="btn">
                            <x-lucide-circle-check-big class="icon text-green" />
                            Selesaikan
                          </button>
                        </form>
                      @endcan

                      @if ($event->recording_url)
                        <a href="{{ $event->recording_url }}" class="btn" target="_blank">
                          <x-lucide-square-play class="icon text-red" />
                          Link Rekaman
                        </a>
                      @elseif ($event->meeting_url)
                        <a href="{{ $event->meeting_url }}" class="btn" target="_blank">
                          <x-lucide-link class="icon" />
                          Link Meeting
                        </a>
                      @endif

                      @can('update', $event)
                        <a href="{{ route('kegiatan.edit', $event->id) }}" class="btn">
                          <x-lucide-pencil class="icon text-blue" />
                          Edit
                        </a>
                      @endcan
                      @can('delete', $event)
                        <a class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-data="{ id: {{ $event->id }} }" x-on:click="$dispatch('set-id', { id })">
                          <x-lucide-trash-2 class="icon text-red" />
                        </a>
                      @endcan
                    </div>
                  </div>
                @endcanany
              @endhaspermission
              <div class="card-body">
                {!! $event->description !!}
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @haspermission('join-event')
    @if ($event->has_joined)
      <div id="modal-event" class="modal fade" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-sm modal-dialog-centered" role="document">
          <div class="modal-content">
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            <div class="modal-status bg-primary"></div>
            <div class="modal-body py-4">
              <h3>Konfirmasi</h3>
              <div>Apakah Anda yakin ingin membatalkan keikutsertaan dalam kegiatan ini? Pembatalan ini akan menghapus status keikutsertaan Anda.</div>
            </div>
            <div class="modal-footer">
              <div class="hstack gap-2 w-100">
                <form method="POST" class="w-100" action="{{ route('kegiatan.join', $event->id) }}">
                  @csrf
                  @method('POST')
                  <button type="submit" class="btn btn-primary w-100">Batalkan</button>
                </form>
                <button type="button" class="btn me-auto w-100" data-bs-dismiss="modal">Tutup</button>
              </div>
            </div>
          </div>
        </div>
      </div>
    @endif
  @endhaspermission
  @can('delete', $event)
    <x-modal-delete baseUrl="{{ route('kegiatan.index') }}" />
  @endcan
@endsection
