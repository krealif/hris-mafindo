@extends('layouts.dashboard', [
    'title' => $title ?? ($user->is(Auth::user()) ? 'Profil' : "Profil {$user->nama}"),
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            @if (Str::startsWith(url()->previous(), [route('user.index'), route('wilayah.index')]))
              <a href="{{ url()->previous() }}" class="btn btn-link px-0 py-1 mb-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @elseif (url()->previous() != url()->current())
              <a href="{{ route('home') }}" class="btn btn-link px-0 py-1 mb-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            @endif
            <h1 class="page-title">
              Profil
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      @if (flash()->message)
        <x-alert type="{{ flash()->class }}">
          {{ flash()->message }}
        </x-alert>
      @endif
      <div class="card mb-3">
        <div class="card-body">
          <div class="d-flex flex-wrap gap-4 justify-content-between">
            <div class="row g-3">
              <div class="col-12 col-lg-auto">
                <img src="{{ $user->foto ? Storage::url($user->foto) : asset('static/img/profile-placeholder.png') }}" class="avatar avatar-xl" />
              </div>
              <div class="col">
                <h2 class="card-title h2 mb-1">{{ $user->nama }}</h2>
                <h3 class="card-subtitle text-dark m-0">{{ $user->role?->label() }}</h3>
                @if ($user->branch?->name)
                  <h3 class="card-subtitle m-0">{{ $user->branch?->name }}</h3>
                @else
                  <h3 class="card-subtitle m-0">{{ $user->email }}</h3>
                @endif
              </div>
            </div>
            <div>
              @can('update', $user)
                <div class="btn-list">
                  @if ($user->is(Auth::user()))
                    <a href="{{ route('user.editProfile') }}" class="btn">
                      <x-lucide-pencil class="icon text-blue" defer />
                      Edit
                    </a>
                    <a href="{{ route('user.settings') }}" class="btn btn-icon">
                      <x-lucide-settings class="icon" defer />
                    </a>
                  @else
                    <a href="{{ route('user.editProfileById', $user->id) }}" class="btn">
                      <x-lucide-pencil class="icon text-blue" defer />
                      Edit
                    </a>
                  @endif
                </div>
              @endcan
            </div>
          </div>
        </div>
      </div>
      @yield('profile-content')
    </div>
  </div>
@endsection
