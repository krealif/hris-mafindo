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
          @can('create', App\Models\Event::class)
            <a href="{{ route('kegiatan.create') }}" class="btn btn-primary">
              <x-lucide-plus class="icon" />
              Tambah
            </a>
          @endcan
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
                <div class="card-body d-flex flex-column justify-content-between">
                  <div>
                    <div class="hstack gap-1 mb-2">
                      @if ($event->has_joined)
                        <span class="badge bg-primary text-white text-uppercase hstack gap-2">
                          <x-lucide-check class="icon" style="width:.75em;height:.75em" defer />
                          Mengikuti
                        </span>
                      @endif
                      <x-badge :case="$event->type" />
                    </div>
                    <a class="card-title h2 line-clamp-3 mb-3" href="{{ route('kegiatan.show', $event->id) }}">{{ $event->name }}</a>
                  </div>
                  <div class="vstack flex-grow-0 gap-1">
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                      <x-lucide-users class="icon" defer />
                      @if ($event->type->value == 'terbatas' && $event->quota)
                        <span>{{ "{$event->participants_count} / {$event->quota} Peserta" }}</span>
                      @else
                        <span>{{ "{$event->participants_count} Peserta" }}</span>
                      @endif
                    </div>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                      <x-lucide-calendar-days class="icon" defer />
                      <span>{{ "{$event->start_date?->translatedFormat('d M Y')}, Pukul {$event->start_date?->translatedFormat('H:i')}" }}</span>
                    </div>
                  </div>
                </div>
                @canany(['update', 'delete'], $event)
                  <div class="card-footer bg-white">
                    <!-- Admin -->
                    <div class="btn-list">
                      @can('update', $event)
                        <a href="{{ route('kegiatan.edit', $event->id) }}" class="btn">
                          <x-lucide-pencil class="icon text-blue" defer />
                          Edit
                        </a>
                      @endcan
                      @can('delete', $event)
                        <a class="btn btn-icon" data-bs-toggle="modal" data-bs-target="#modal-delete" x-data="{ id: {{ $event->id }} }" x-on:click="$dispatch('set-id', { id })">
                          <x-lucide-trash-2 class="icon text-red" defer />
                        </a>
                      @endcan
                    </div>
                  </div>
                @endcanany
              </div>
            </div>
          @endforeach
        </x-dt.datacard>
      </div>
    </div>
  </div>
  @haspermission('delete-event')
    <x-modal-delete baseUrl="{{ route('kegiatan.index') }}" />
  @endhaspermission
@endsection
