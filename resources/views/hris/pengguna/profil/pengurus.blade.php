@extends('hris.pengguna.profil._main')

@section('profile-content')
  <div class="row row-deck row-cards g-3">
    <div class="col-12 col-md-6">
      <div class="card card-mafindo">
        <div class="card-header">
          <h3 class="card-title">Informasi Pengurus</h3>
        </div>
        <div class="card-body">
          <div class="datagrid">
            <x-datagrid-item title="Email" content="{{ $user->email }}" />
            <x-datagrid-item title="Wilayah" content="{{ $user->branch?->name }}" />
            <x-datagrid-item title="Sekretaris 1" content="{{ $user->branch?->staff->sekretaris1 }}" />
            <x-datagrid-item title="Sekretaris 2" content="{{ $user->branch?->staff->sekretaris2 }}" />
            <x-datagrid-item title="Bendahara 1" content="{{ $user->branch?->staff->bendahara1 }}" />
            <x-datagrid-item title="Bendahara 2" content="{{ $user->branch?->staff->bendahara2 }}" />
          </div>
        </div>
      </div>
    </div>
  </div>
@endsection
