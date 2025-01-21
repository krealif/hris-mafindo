@extends('layouts.dashboard', [
    'title' => 'Arsip Kegiatan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <h1 class="page-title">
              Arsip Kegiatan
            </h1>
          </div>
        </div>
      </div>
    </div>
    <!-- Body -->
    <div class="page-body">
      <div class="container-xl">
        @include('hris.kegiatan._tabs-index')
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
        <x-dt.datacard search="name" searchPlaceholder="Nama Kegiatan" :collection="$events">
          <x-slot:filterForm>
            <!-- Table filter -->
            <div class="row gx-4 gy-3">
              <div class="col-12 col-md-6 col-lg-3">
                <label for="type" class="form-label">Tipe</label>
                <x-form.select name="type" :options="App\Enums\EventTypeEnum::labels()" selected="{{ request()->filter['type'] ?? '' }}" placeholder="Semua" :showError=false />
              </div>
              <div class="col-12 col-md-6 col-lg-3">
                <label for="start_date" class="form-label">Tanggal</label>
                <x-dt.date-filter name="start_date" value="{{ request()->filter['start_date'] ?? '' }}" />
              </div>
            </div>
          </x-slot>
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
                      <span>
                        {{ "{$event->start_date?->translatedFormat('d M Y')}, {$event->start_date?->translatedFormat('H:i')}" }}
                        <strong>(SELESAI)</strong>
                      </span>
                    </div>
                  </div>
                </div>
                @can('update', $event)
                  <div class="card-footer bg-white">
                    <!-- Admin -->
                    <div class="btn-list">
                      <a href="{{ route('kegiatan.edit', $event->id) }}" class="btn">
                        <x-lucide-pencil class="icon text-blue" defer />
                        Edit
                      </a>
                    </div>
                  </div>
                @endcan
              </div>
            </div>
          @endforeach
        </x-dt.datacard>
      </div>
    </div>
  </div>
@endsection
