@extends('hris.pengguna.edit-profil._main')

@section('edit-form')
  <div class="row g-3">
    <div class="col-12 col-md-3 mb-3 mb-md-0">
      <div class="card card-mafindo sticky-top">
        <div class="card-header">
          <h3 class="card-title d-flex align-items-center gap-2">
            <x-lucide-list class="icon" />
            Daftar Isi
          </h3>
        </div>
        <nav class="list-group list-group-flush">
          <a class="list-group-item list-group-item-action toc-item" href="#informasi-pribadi">
            <x-lucide-chevron-right class="icon" defer />
            Informasi Wilayah
          </a>
        </nav>
      </div>
    </div>
    <div class="col-12 col-md-9">
      <form method="POST" action="{{ route('user.updateProfile', $user->id) }}" class="vstack gap-3" x-data="" enctype="multipart/form-data" autocomplete="off">
        @csrf
        @method('PATCH')
        <div id="informasi-pribadi" class="card card-mafindo">
          <div class="card-header">
            <h2 class="card-title">Informasi Wilayah</h2>
          </div>
          <div class="card-body">
            <div class="mb-3">
              <label for="coordinatorName" class="form-label required">Koordinator</label>
              <x-form.input name="coordinatorName" type="text" value="{{ old('coordinatorName', $user->nama) }}" required />
            </div>
            @if ($errors->has('staff.*'))
              <x-alert class="alert-danger">
                <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                <ul class="mt-2 mb-0" style="margin-left: -1rem">
                  @foreach ($errors->get('staff.*') as $e)
                    @foreach ($e as $error)
                      <li>{{ $error }}</li>
                    @endforeach
                  @endforeach
                </ul>
              </x-alert>
            @endif
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="sekretaris1" class="form-label">Sekretaris 1</label>
                <x-form.input id="sekretaris1" name="staff[sekretaris1]" type="text" value="{{ old('staff.sekretaris1', $user->branch?->staff->sekretaris1) }}" />
              </div>
              <div class="col-12 col-md-6">
                <label for="sekretaris2" class="form-label">Sekretaris 2</label>
                <x-form.input id="sekretaris2" name="staff[sekretaris2]" type="text" value="{{ old('staff.sekretaris2', $user->branch?->staff->sekretaris2 ?? '') }}" />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="bendahara1" class="form-label">Bendahara 1</label>
                <x-form.input id="bendahara1" name="staff[bendahara1]" type="text" value="{{ old('staff.bendahara1', $user->branch?->staff->bendahara1) }}" />
              </div>
              <div class="col-12 col-md-6">
                <label for="bendahara2" class="form-label">Bendahara 2</label>
                <x-form.input id="bendahara2" name="staff[bendahara2]" type="text" value="{{ old('staff.bendahara2', $user->branch?->staff->bendahara2) }}" />
              </div>
            </div>
          </div>
        </div>

        <div class="card bg-primary-lt shadow position-sticky bottom-0 z-3">
          <input type="hidden" name="_isDraft" x-model="isDraft">
          <div class="card-body btn-list">
            <button class="btn btn-primary">
              <x-lucide-save class="icon" />
              Simpan
            </button>
            <a href="{{ route('user.profile', $user->id) }}" class="btn">Batal</a>
          </div>
        </div>
      </form>
    </div>
  </div>
@endsection
