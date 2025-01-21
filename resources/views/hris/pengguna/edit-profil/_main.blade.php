@extends('layouts.dashboard', [
    'title' => $title ?? ($user->is(Auth::user()) ? 'Edit Profil' : "Edit Profil {$user->nama}"),
])

@section('content')
  <div class="page-wrapper">
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <a href="{{ route('user.profile', $user->id) }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
            <h1 class="page-title">
              Edit Profil
              @if ($user->isNot(Auth::user()))
                {{ $user->nama }}
              @endif
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      @if ($errors->any())
        <x-alert class="alert-danger">
          <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
          <ul class="mt-2 mb-0" style="margin-left: -1rem">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </x-alert>
      @endif
      @yield('edit-form')
    </div>
  </div>
@endsection
