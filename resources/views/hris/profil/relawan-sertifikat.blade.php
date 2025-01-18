@extends('layouts.dashboard', [
    'title' => 'Profil Saya',
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Profil Saya
          </h1>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="card mb-3">
        <div class="card-body">
          <div class="row g-3">
            <div class="col-12 col-lg-auto">
              <img src="{{ $user->foto ? Storage::url($user->foto) : asset('static/img/profile-placeholder.png') }}" class="avatar avatar-xl" />
            </div>
            <div class="col">
              <h2 class="card-title h2 mb-3">{{ $user->nama }}</h2>
              <h3 class="card-subtitle text-dark mb-2">{{ $user->role?->label() }}</h3>
              <h3 class="card-subtitle mb-2">{{ $user->branch?->name }}</h3>
            </div>
          </div>
        </div>
      </div>
      @include('hris.profil._tabs-relawan')
      <x-dt.datacard :collection="$eventCertificate">
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
    </div>
  </div>
@endsection
