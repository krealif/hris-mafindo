@extends('layouts.dashboard', [
    'title' => 'Kegiatan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <h1 class="page-title">
              Kegiatan
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        @include('hris.kegiatan._tabs-index')
        <x-dt.datacard search="title" :collection="$events">
          @foreach ($events as $event)
            <div class="col-12 col-sm-6 col-lg-4 col-xxl-3">
              <div class="card card-mafindo">
                <a href="{{ route('kegiatan.show', $event->id) }}">
                  <img class="card-img-top" src="{{ Storage::url($event->cover) }}">
                </a>
                <div class="card-body">
                  <div class="hstack gap-1 mb-2">
                    <span class="badge bg-primary text-white text-uppercase">
                      Mengikuti
                    </span>
                    <x-badge :case="$event->type" />
                  </div>
                  <a class="card-title h2 line-clamp-3 mb-3" href="{{ route('kegiatan.show', $event->id) }}">{{ $event->name }}</a>
                  <div class="vstack gap-1">
                    @if ($event->type->value == 'terbatas' && isset($event->participants_count))
                      <div class="d-flex flex-wrap gap-2 align-items-center">
                        <x-lucide-users class="icon" defer />
                        <span>{{ "{$event->participants_count} / {$event->quota} Peserta" }}</span>
                      </div>
                    @endif
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                      <x-lucide-calendar-days class="icon" defer />
                      <span>{{ "{$event->start_date?->translatedFormat('d M Y')}, Pukul {$event->start_date?->translatedFormat('H:i')}" }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </x-dt.datacard>
      </div>
    </div>
  </div>
@endsection
