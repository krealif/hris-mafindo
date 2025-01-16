@extends('layouts.dashboard', [
    'title' => 'Profil',
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <h1 class="page-title">
            Profil
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
              <h3 class="card-subtitle mb-2">{{ $user->email }}</h3>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
