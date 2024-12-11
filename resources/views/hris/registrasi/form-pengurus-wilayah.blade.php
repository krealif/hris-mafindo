<div class="row g-3">
  <div class="col-12">
    @if (flash()->message)
      <x-alert type="{{ flash()->class }}">
        {{ flash()->message }}
      </x-alert>
    @endif
    <div class="card card-mafindo border-0">
      <div class="card-header border border-bottom-0" style="border-color: var(--tblr-border-color) !important">
        <h3 class="d-flex align-items-center gap-2 mb-0">
          <x-lucide-chevrons-right class="icon" />
          Tahapan
          @if ($registration?->status && $registration?->status != 'selesai')
            <span class="badge bg-secondary text-white">{{ ucfirst($registration->status) }}</span>
          @endif
        </h3>
      </div>
      <x-registration-step :data="App\Enums\RegistrationLamaStepEnum::labels()" step="{{ $registration?->step }}" />
      @if ($registration?->status == 'revisi')
        <div class="card-body border border-top-0">
          <h4 class="fs-3 text-red">REVISI</h4>
          <p>{{ $registration->message }}</p>
        </div>
      @endif
    </div>
  </div>
  @if (in_array($registration?->status, [null, 'draft', 'revisi']))
    <div class="col-12 col-md-3 mb-3 mb-md-0">
      <div class="card card-mafindo sticky-top">
        <div class="card-header">
          <h3 class="card-title d-flex align-items-center gap-2">
            <x-lucide-list class="icon" />
            Daftar Isi
          </h3>
        </div>
        <nav class="list-group list-group-flush">
          <a class="list-group-item list-group-item-action p-2" href="#informasi-pribadi">
            <x-lucide-arrow-down-right class="icon" defer />
            Informasi Wilayah
          </a>
        </nav>
      </div>
    </div>
    <div class="col-12 col-md-9">
      <form method="POST" action="{{ route('registration.store', $type) }}" class="vstack gap-2" x-data="{ isDraft: false }" x-bind:novalidate="isDraft" enctype="multipart/form-data"
        autocomplete="off">
        @csrf
        <div id="informasi-pribadi" class="card card-mafindo">
          <div class="card-header">
            <h2 class="card-title">Informasi Wilayah</h2>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="branch" class="form-label required">Wilayah</label>
                <x-form.tom-select id="branch" name="branch_id" :options=$branches selected="{{ old('branch', Auth::user()->branch_id) }}" required>
                  <option value="">Pilih wilayah</option>
                </x-form.tom-select>
              </div>
              <div class="col-12 col-md-6">
                <label for="nama" class="form-label required">Koordinator</label>
                <x-form.input name="nama" type="text" value="{{ old('nama', Auth::user()->nama) }}" required />
              </div>
            </div>
            @if ($errors->has('pengurus.*'))
              <x-alert class="alert-danger">
                <div>Tolong periksa kembali data yang Anda masukkan.</div>
                <ul class="mt-2 mb-0" style="margin-left: -1rem">
                  @foreach ($errors->get('pengurus.*') as $e)
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
                <x-form.input id="sekretaris1" name="pengurus[sekretaris1]" type="text"
                  value="{{ old('pengurus.sekretaris1', Auth::user()->branch?->pengurus->sekretaris1) }}" />
              </div>
              <div class="col-12 col-md-6">
                <label for="sekretaris2" class="form-label">Sekretaris 2</label>
                <x-form.input id="sekretaris2" name="pengurus[sekretaris2]" type="text"
                  value="{{ old('pengurus.sekretaris2', Auth::user()->branch?->pengurus->sekretaris2) }}" />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="bendahara1" class="form-label">Bendahara 1</label>
                <x-form.input id="bendahara1" name="pengurus[bendahara1]" type="text" value="{{ old('pengurus.bendahara1', Auth::user()->branch?->pengurus->bendahara1) }}" />
              </div>
              <div class="col-12 col-md-6">
                <label for="bendahara2" class="form-label">Bendahara 2</label>
                <x-form.input id="bendahara2" name="pengurus[bendahara2]" type="text" value="{{ old('pengurus.bendahara2', Auth::user()->branch?->pengurus->bendahara2) }}" />
              </div>
            </div>
          </div>
        </div>

        <div class="card bg-primary-lt shadow position-sticky bottom-0 z-3">
          <input type="hidden" name="mode" x-bind:value="isDraft ? 'draft' : 'submit'">
          <div class="card-body btn-list">
            <button class="btn btn-primary" @click="isDraft = false">
              <x-lucide-send class="icon" />
              Ajukan
            </button>
            <button class="btn btn-secondary" @click="isDraft = true">
              <x-lucide-save class="icon" />
              Simpan Sementara
            </button>
          </div>
        </div>
      </form>
    </div>
  @endif
</div>
