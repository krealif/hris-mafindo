@extends('hris.pengguna.profil._main')

@section('profile-content')
  @include('hris.pengguna.profil._tabs-relawan')
  <x-dt.datacard search="name" searchPlaceholder="Cari Nama Kegiatan" :collection="$eventCertificate">
    @foreach ($eventCertificate as $event)
      <div class="col-12">
        <div class="card card-mafindo">
          <div class="card-body">
            <div class="row g-3">
              <div class="col-auto">
                <span class="bg-yellow-lt text-white avatar avatar-md" style="--tblr-avatar-size:3.25rem">
                  <x-lucide-award width="32" height="32" defer />
                </span>
              </div>
              <div class="col d-flex flex-column justify-content-between">
                <h2 class="card-title mb-1">{{ $event->name }}</h2>
                <span class="text-muted">
                  {{ "{$event->start_date?->translatedFormat('d M Y')}, Pukul {$event->start_date?->translatedFormat('H:i')}" }}
                </span>
              </div>
            </div>
            <div class="mt-3">
              <a href="{{ route('sertifikat.downloadForRelawan', $event->id) }}" class="btn" target="_blank">
                <x-lucide-download class="icon text-green" />
                Download
              </a>
            </div>
          </div>
        </div>
      </div>
    @endforeach
  </x-dt.datacard>
@endsection
