@extends('layouts.dashboard', [
    'title' => ($tempUser ? $tempUser->nama : 'Tambah') . ' | Migrasi Data Relawan',
])

@php
  $tempUser = $tempUser ?? null;
  $detail = $detail ?? null;
  $action = Route::currentRouteName() == 'migrasi.create' ? route('migrasi.store') : route('migrasi.update', $tempUser?->id);
@endphp

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="d-flex gap-2 justify-content-between align-items-center">
          <div class="col">
            <div class="mb-1">
              <a href="{{ route('migrasi.index') }}" class="btn btn-link px-0 py-1">
                <x-lucide-arrow-left class="icon" />
                Kembali
              </a>
            </div>
            @if ($tempUser)
              <h1 class="page-title">{{ $tempUser->nama }} | Edit Data</h1>
            @else
              <h1 class="page-title">Tambah Data</h1>
            @endif
          </div>
          @if ($tempUser)
            <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn" x-data="{ id: {{ $tempUser->id }} }" x-on:click="$dispatch('set-id', { id })">
              <x-lucide-trash-2 class="icon text-red" />
              Hapus
            </button>
          @endif
        </div>
      </div>
    </div>
    <div class="page-body">
      <div class="container-xl">
        <div class="row g-3">
          <div class="col-12 only-alert">
            @if (flash()->message)
              <x-alert type="{{ flash()->class }}" class="m-0">
                {{ flash()->message }}
              </x-alert>
            @endif
            @if ($errors->any())
              <x-alert class="alert-danger m-0">
                <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                <ul class="mt-2 mb-0" style="margin-left: -1rem">
                  @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                  @endforeach
                </ul>
              </x-alert>
            @endif
          </div>
          <div class="col-12 col-md-3 mb-3 mb-md-0">
            <div class="card card-mafindo sticky-top">
              <div class="card-header">
                <h2 class="card-title d-flex align-items-center gap-2">
                  <x-lucide-list class="icon" />
                  Daftar Isi
                </h2>
              </div>
              <nav class="list-group list-group-flush">
                <a class="list-group-item list-group-item-action p-2" href="#informasi-pribadi">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Informasi Pribadi
                </a>
                <a class="list-group-item list-group-item-action p-2" href="#kontak">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Kontak
                </a>
                <a class="list-group-item list-group-item-action p-2" href="#keanggotaan">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Keanggotaan Mafindo
                </a>
                <a class="list-group-item list-group-item-action p-2" href="#bidang">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Bidang
                </a>
                <a class="list-group-item list-group-item-action p-2" href="#pendidikan">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Riwayat Pendidikan
                </a>
                <a class="list-group-item list-group-item-action p-2" href="#pekerjaan">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Riwayat Pekerjaan
                </a>
                <a class="list-group-item list-group-item-action p-2" href="#sertifikat">
                  <x-lucide-arrow-down-right class="icon" defer />
                  Sertifikat Keahlian
                </a>
              </nav>
            </div>
          </div>
          <div class="col-12 col-md-9">
            <form method="POST" action="{{ $action }}" class="vstack gap-3" autocomplete="off" x-data="">
              @csrf
              @if (Route::currentRouteName() == 'migrasi.edit')
                @method('PATCH')
              @endif
              <div id="informasi-pribadi" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Informasi Pribadi</h2>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="nama" class="form-label required">Nama Lengkap</label>
                      <x-form.input name="nama" type="text" value="{{ old('nama', $tempUser?->nama) }}" required />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="email" class="form-label required">Email</label>
                      <x-form.input name="email" type="email" value="{{ old('email', $tempUser?->email) }}" required />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="panggilan" class="form-label">Nama Panggilan</label>
                      <x-form.input name="panggilan" type="text" value="{{ old('panggilan', $detail?->panggilan) }}" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="tgl-lahir" class="form-label">Tanggal Lahir</label>
                      <x-form.flatpickr name="tgl_lahir" maxDate="today" value="{{ old('tgl_lahir', $detail?->tgl_lahir) }}" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="gender" class="form-label">Jenis Kelamin</label>
                      <x-form.select name="gender" :options="App\Enums\GenderEnum::labels()" selected="{{ old('gender', $detail?->gender) }}" placeholder="" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="agama" class="form-label">Agama</label>
                      <x-form.select name="agama" :options="App\Enums\AgamaEnum::labels()" selected="{{ old('agama', $detail?->agama) }}" placeholder="" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="alamat" class="form-label">Alamat Domisili Saat Ini</label>
                      <x-form.input name="alamat" type="text" value="{{ old('alamat', $detail?->alamat) }}" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="disabilitas" class="form-label">Disabilitas</label>
                      <x-form.input name="disabilitas" type="text" value="{{ old('disabilitas', $detail?->disabilitas) }}" />
                    </div>
                  </div>
                </div>
              </div>

              <div id="kontak" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Kontak</h2>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="no-wa" class="form-label">Nomor Whatsapp</label>
                      <x-form.input name="no_wa" type="tel" x-mask="9999999999999" placeholder="08xxxxxxxxxx" value="{{ old('no_wa', $detail?->no_wa) }}" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="no-hp" class="form-label">Nomor HP</label>
                      <x-form.input name="no_hp" type="tel" x-mask="9999999999999" placeholder="Tuliskan jika berbeda dengan nomor Whatsapp"
                        value="{{ old('no_hp', $detail?->no_hp) }}" />
                    </div>
                  </div>
                  @if ($errors->has('medsos.*'))
                    <x-alert class="alert-danger">
                      <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                      <ul class="mt-2 mb-0" style="margin-left: -1rem">
                        @foreach ($errors->get('medsos.*') as $e)
                          @foreach ($e as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        @endforeach
                      </ul>
                    </x-alert>
                  @endif
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="medsos-facebook" class="form-label">Akun Facebook</label>
                      <x-form.input id="medsos-facebook" name="medsos[facebook]" type="text" placeholder="Nama Akun"
                        value="{{ old('medsos.facebook', $detail?->medsos?->facebook) }}" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="medsos-instagram" class="form-label">Akun Instagram</label>
                      <div class="input-group mb-2">
                        <span class="input-group-text">@</span>
                        <x-form.input id="medsos-instagram" name="medsos[instagram]" type="text" placeholder="username"
                          value="{{ old('medsos.instagram', $detail?->medsos?->instagram) }}" />
                      </div>
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="medsos-tiktok" class="form-label">Akun Tiktok</label>
                      <div class="input-group mb-2">
                        <span class="input-group-text">@</span>
                        <x-form.input id="medsos-tiktok" name="medsos[tiktok]" type="text" placeholder="username"
                          value="{{ old('medsos.tiktok', $detail?->medsos?->tiktok) }}" />
                      </div>
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="medsos-twitter" class="form-label">Akun Twitter</label>
                      <div class="input-group mb-2">
                        <span class="input-group-text">@</span>
                        <x-form.input id="medsos-twitter" name="medsos[twitter]" type="text" placeholder="username"
                          value="{{ old('medsos.twitter', $detail?->medsos?->twitter) }}" />
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <div id="keanggotaan" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Keanggotaan di Mafindo</h2>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="tahun-bergabung" class="form-label">Tahun Bergabung</label>
                      <x-form.input name="thn_bergabung" type="text" x-mask="9999" pattern="\d{4}" value="{{ old('thn_bergabung', $detail?->thn_bergabung) }}" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="branch" class="form-label">Wilayah</label>
                      <x-form.tom-select id="branch" name="branch_id" :options=$branches selected="{{ old('branch', $tempUser?->branch_id) }}" placeholder="" />
                    </div>
                  </div>
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="pdr" class="form-label">Keikutsertaan Pelatihan Dasar Relawan</label>
                      <x-form.select name="pdr" :options="[0 => 'Belum Pernah', 1 => '1', 2 => '2', 3 => '3']" selected="{{ old('pdr', $detail?->pdr) }}" />
                    </div>
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="no-relawan" class="form-label">Nomor Kartu Relawan</label>
                      <x-form.input name="no_relawan" type="text" value="{{ old('no_relawan', $tempUser?->no_relawan) }}" />
                    </div>
                  </div>
                </div>
              </div>

              <div id="bidang" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Bidang</h2>
                </div>
                <div class="card-body">
                  <div class="row mb-3">
                    <div class="col-12 col-md-6 mb-3 mb-md-0">
                      <label for="bidang-keahlian" class="form-label">Bidang Keahlian (jika ada)</label>
                      <x-form.input name="bidang_keahlian" type="text" value="{{ old('bidang_keahlian', $detail?->bidang_keahlian) }}" />
                    </div>
                    <div class="col-12 col-md-6">
                      <label for="bidang-mafindo" class="form-label">Bidang yang Ingin Dikembangkan</label>
                      <x-form.select name="bidang_mafindo" :options="App\Enums\BidangMafindoEnum::labels()" selected="{{ old('bidang_mafindo', $detail?->bidang_mafindo) }}" />
                    </div>
                  </div>
                </div>
              </div>

              <div id="pendidikan" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Riwayat Pendidikan</h2>
                </div>
                <div class="card-body" x-data="pendidikan">
                  @if ($errors->has('pendidikan.*'))
                    <x-alert class="alert-danger">
                      <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                      <ul class="mt-2 mb-0" style="margin-left: -1rem">
                        @foreach ($errors->get('pendidikan.*') as $e)
                          @foreach ($e as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        @endforeach
                      </ul>
                    </x-alert>
                  @endif
                  <template x-for="(row, index) in rows" :key="index">
                    <div class="position-relative">
                      <div class="row mb-3">
                        <div class="col-12 col-md-2 mb-1 mb-md-0">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.select id="pendidikan-tingkat" x-model="row.tingkat" :options="App\Enums\TingkatPendidikanEnum::labels()" x-bind:name="`pendidikan[${index}][tingkat]`" :showError=false
                                placeholder="" required />
                              <label for="pendidikan-tingkat">Tingkat</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-5 mb-1 mb-md-0">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="pendidikan-institusi" x-model="row.institusi" x-bind:name="`pendidikan[${index}][institusi]`" type="text" maxlength="255"
                                placeholder="Institusi" :showError=false required />
                              <label for="pendidikan-institusi">Institusi</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-5">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="pendidikan-jurusan" x-model="row.jurusan" x-bind:name="`pendidikan[${index}][jurusan]`" type="text" maxlength="255"
                                placeholder="Jurusan" :showError=false required />
                              <label for="pendidikan">Jurusan/Program Studi</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 0">
                        <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" x-on:click="del(index)">
                          <x-lucide-x class="icon text-red" style="max-width: unset" defer />
                        </div>
                      </div>
                    </div>
                  </template>
                  <button class="btn" type="button" x-on:click="add()">
                    <x-lucide-plus class="icon" />
                    Tambah
                  </button>
                </div>
              </div>

              <div id="pekerjaan" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Riwayat Pekerjaan</h2>
                </div>
                <div class="card-body" x-data="pekerjaan">
                  @if ($errors?->has('pekerjaan.*'))
                    <x-alert class="alert-danger">
                      <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                      <ul class="mt-2 mb-0" style="margin-left: -1rem">
                        @foreach ($errors->get('pekerjaan.*') as $e)
                          @foreach ($e as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        @endforeach
                      </ul>
                    </x-alert>
                  @endif
                  <template x-for="(row, index) in rows" :key="index">
                    <div class="position-relative">
                      <div class="row mb-3">
                        <div class="col-12 col-md-4 mb-1 mb-md-0">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="pekerjaan-jabatan" x-model="row.jabatan" x-bind:name="`pekerjaan[${index}][jabatan]`" type="text" maxlength="255"
                                placeholder="jabatan" :showError=false required />
                              <label for="pekerjaan-jabatan">Jabatan</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-4 mb-1 mb-md-0">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="pekerjaan-lembaga" x-model="row.lembaga" x-bind:name="`pekerjaan[${index}][lembaga]`" type="text" maxlength="255"
                                placeholder="lembaga" :showError=false required />
                              <label for="pekerjaan-lembaga">Lembaga</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-4">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="pekerjaan-tahun" x-model="row.tahun" x-bind:name="`pekerjaan[${index}][tahun]`" type="text" pattern="\d{4}-\d{4}"
                                x-mask="9999-9999" maxlength="255" placeholder="Tahun" :showError=false required />
                              <label for="pekerjaan-tahun">Tahun (YYYY-YYYY)</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 0">
                        <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" x-on:click="del(index)">
                          <x-lucide-x class="icon text-red" style="max-width: unset" defer />
                        </div>
                      </div>
                    </div>
                  </template>
                  <button class="btn" type="button" x-on:click="add()">
                    <x-lucide-plus class="icon" />
                    Tambah
                  </button>
                </div>
              </div>

              <div id="sertifikat" class="card card-mafindo">
                <div class="card-header">
                  <h2 class="card-title">Sertifikat Keahlian / Kompetensi (Sertifikasi)</h2>
                </div>
                <div class="card-body" x-data="sertifikat">
                  @if ($errors?->has('sertifikat.*'))
                    <x-alert class="alert-danger">
                      <div>Error! Tolong periksa kembali data yang Anda masukkan.</div>
                      <ul class="mt-2 mb-0" style="margin-left: -1rem">
                        @foreach ($errors->get('sertifikat.*') as $e)
                          @foreach ($e as $error)
                            <li>{{ $error }}</li>
                          @endforeach
                        @endforeach
                      </ul>
                    </x-alert>
                  @endif
                  <template x-for="(row, index) in rows" :key="index">
                    <div class="position-relative">
                      <div class="row mb-3">
                        <div class="col-12 col-md-6 mb-1 mb-md-0">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="sertifikat-nama" x-model="row.nama" x-bind:name="`sertifikat[${index}][nama]`" type="text" maxlength="255" placeholder="Nama"
                                :showError=false required />
                              <label for="sertifikat-nama">Nama</label>
                            </div>
                          </div>
                        </div>
                        <div class="col-12 col-md-6">
                          <div class="form-floating">
                            <div class="form-floating">
                              <x-form.input id="sertifikat-masa" x-model="row.masa" x-bind:name="`sertifikat[${index}][masa]`" type="text" pattern="\d{4}-\d{4}"
                                x-mask="9999-9999" maxlength="255" placeholder="Masa Berlaku" :showError=false required />
                              <label for="sertifikat-masa">Masa Berlaku</label>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 0">
                        <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" x-on:click="del(index)">
                          <x-lucide-x class="icon text-red" style="max-width: unset" defer />
                        </div>
                      </div>
                    </div>
                  </template>
                  <button class="btn" type="button" x-on:click="add()">
                    <x-lucide-plus class="icon" />
                    Tambah
                  </button>
                </div>
              </div>

              <div class="card bg-primary-lt shadow position-sticky bottom-0 z-3">
                <div class="card-body btn-list">
                  <button class="btn btn-primary">
                    <x-lucide-save class="icon" />
                    Simpan
                  </button>
                  <a href="{{ route('migrasi.index') }}" class="btn">Batal</a>
                </div>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
  @if ($tempUser)
    <x-modal-delete baseUrl="{{ route('migrasi.index') }}" />
  @endif
  <script>
    function createDynamicList(key, data) {
      return () => ({
        key,
        rows: data ?? [],

        // Actions
        add() {
          this.rows.push({});
        },
        del(index) {
          this.rows.splice(index, 1);
        },
      });
    }

    document.addEventListener('alpine:init', () => {
      const dataPendidikan = {{ Js::from(old('pendidikan', $detail?->pendidikan)) }};
      Alpine.data('pendidikan', createDynamicList('pendidikan', dataPendidikan));

      const dataPekerjaan = {{ Js::from(old('pekerjaan', $detail?->pekerjaan)) }};
      Alpine.data('pekerjaan', createDynamicList('pekerjaan', dataPekerjaan));

      const dataSertifikat = {{ Js::from(old('sertifikat', $detail?->sertifikat)) }};
      Alpine.data('sertifikat', createDynamicList('sertifikat', dataSertifikat));

      Alpine.data('imgPreview', () => ({
        img: null,
        newImg: null,
        cancelUpload() {
          this.newImg = null;
          this.$refs.imgInput.value = '';
        },
        handleFileUpload(event) {
          const file = event.target.files[0];
          if (file) {
            const reader = new FileReader();
            reader.onload = () => {
              this.newImg = reader.result;
            };
            reader.readAsDataURL(file);
          }
        }
      }));
    });
  </script>
@endsection
