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
          @if ($registration?->status)
            <span class="badge bg-secondary text-white">{{ ucfirst($registration->status) }}</span>
          @endif
        </h3>
      </div>
      <x-registration-step step="{{ $registration?->step ?? 'mengisi' }}" />
      @if ($registration?->status == 'revisi')
        <div class="card-body border border-top-0">
          <h4 class="fs-3 text-red">REVISI</h4>
          <p>Lorem ipsum dolor sit amet consectetur adipisicing elit. In corporis aspernatur aut doloribus ullam fugit repudiandae. Atque ullam molestias delectus eius ratione
            dolorum commodi aspernatur doloribus tenetur laudantium quae accusantium ad vero, at eum, debitis ab illo error ut beatae est dicta enim? Ratione exercitationem eum
            sequi
            quis ab saepe quae fugit ipsam sit velit, nostrum libero ducimus laudantium eos odit esse, fuga soluta id, cum perspiciatis quaerat quasi impedit! Dicta optio, aut sit,
            dolore velit voluptatibus debitis corrupti nesciunt harum hic suscipit, deserunt praesentium! Quod, id odit ratione nobis iure deserunt, dolorum distinctio
            voluptatibus,
            doloremque natus provident possimus harum!</p>
        </div>
      @endif
    </div>
  </div>
  @if ($registration?->step === 'mengisi' || is_null($registration?->step))
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
            Informasi Pribadi
          </a>
          <a class="list-group-item list-group-item-action p-2" href="#foto-profil">
            <x-lucide-arrow-down-right class="icon" defer />
            Foto Profil
          </a>
          <a class="list-group-item list-group-item-action p-2" href="#keanggotaan">
            <x-lucide-arrow-down-right class="icon" defer />
            Keanggotaan
          </a>
          <a class="list-group-item list-group-item-action p-2" href="#bidang">
            <x-lucide-arrow-down-right class="icon" defer />
            Bidang Keahlian
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
            Sertifikat
          </a>
        </nav>
      </div>
    </div>
    <div class="col-12 col-md-9">
      <form method="POST" class="vstack gap-2" x-data="{ isDraft: false }" x-bind:novalidate="isDraft" enctype="multipart/form-data" autocomplete="off">
        @csrf
        <div id="informasi-pribadi" class="card card-mafindo">
          <div class="card-header">
            <h2 class="card-title">Informasi Pribadi</h2>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="nama" class="form-label required">Nama Lengkap</label>
                <x-form.input name="nama" type="text" value="{{ old('nama', Auth::user()->nama) }}" required />
              </div>
              <div class="col-12 col-md-6">
                <label for="panggilan" class="form-label required">Nama Panggilan</label>
                <x-form.input name="panggilan" type="text" value="{{ old('panggilan', $detail?->panggilan) }}" required />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="tgl-lahir" class="form-label required">Tanggal Lahir</label>
                <x-form.input name="tgl_lahir" type="date" value="{{ old('tgl_lahir', $detail?->tgl_lahir) }}" required />
              </div>
              <div class="col-12 col-md-6">
                <label for="gender" class="form-label required">Jenis Kelamin</label>
                <x-form.select name="gender" :options="App\Enums\GenderEnum::labels()" selected="{{ old('gender', $detail?->gender) }}" required />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="agama" class="form-label required">Agama</label>
                <x-form.select name="agama" :options="App\Enums\AgamaEnum::labels()" selected="{{ old('agama', $detail?->agama) }}" required />
              </div>
              <div class="col-12 col-md-6">
                <label for="alamat" class="form-label required">Alamat Domisili Saat Ini</label>
                <x-form.input name="alamat" type="text" value="{{ old('alamat', $detail?->alamat) }}" required />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="disabilitas" class="form-label required">Disabilitas</label>
                <x-form.input name="disabilitas" type="text" placeholder="Tanpa disabilitas / tunanetra / tunarungu / tundadaksa"
                  value="{{ old('disabilitas', $detail?->disabilitas) }}" required />
              </div>
            </div>
          </div>
        </div>

        <div id="foto-profil" class="card card-mafindo">
          <div class="card-header">
            <h2 class="card-title">Foto Profil</h2>
          </div>
          <div class="card-body" x-data="imgPreview">
            <div class="row g-3">
              <div class="col-auto">
                <img id="frame" class="avatar avatar-xl" x-bind:src="newImg || img" />
              </div>
              <div class="col">
                <label for="foto" @class(['form-label', 'required' => !Auth::user()->foto])>{{ Auth::user()->foto ? 'Ganti Foto' : 'Upload Foto' }}</label>
                <div class="row g-2">
                  <div class="col">
                    <x-form.input name="foto" type="file" x-ref="imgInput" @change="handleFileUpload" accept=".jpg,.jpeg,.png" :required="!Auth::user()->foto" />
                  </div>
                  <div class="col-auto" x-show="newImg">
                    <button @click="cancelUpload" type="button" class="btn btn-icon">
                      <x-lucide-image-minus class="icon text-red" />
                    </button>
                  </div>
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
                <label for="tahun-bergabung" class="form-label required">Tahun Bergabung</label>
                <x-form.input name="tahun_bergabung" type="text" x-mask="9999" value="{{ old('tahun_bergabung', $detail?->tahun_bergabung) }}" required />
              </div>
              <div class="col-12 col-md-6">
                <label for="branch" class="form-label required">Wilayah</label>
                <x-form.tom-select id="branch" name="branch_id" :options=$branches selected="{{ old('branch', Auth::user()->branch_id) }}" required>
                  <option value="">Pilih wilayah</option>
                </x-form.tom-select>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="pdr" class="form-label required">Keikutsertaan Pelatihan Dasar Relawan</label>
                <x-form.select name="pdr" :options="[0 => '0 (Belum Pernah)', 1 => '1', 2 => '2', 3 => '3']" selected="{{ old('agama', $detail?->agama) }}" required />
              </div>
            </div>
          </div>
        </div>

        <div id="bidang" class="card card-mafindo">
          <div class="card-header">
            <h2 class="card-title">Bidang Keahlian</h2>
          </div>
          <div class="card-body">
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="bidang-keahlian" class="form-label">Bidang Keahlian (jika ada)</label>
                <x-form.input name="bidang_keahlian" type="text" value="{{ old('bidang_keahlian', $detail?->bidang_keahlian) }}" />
              </div>
              <div class="col-12 col-md-6">
                <label for="bidang-mafindo" class="form-label required">Bidang yang Ingin Dikembangkan</label>
                <x-form.select name="bidang-mafindo" :options="App\Enums\BidangMafindoEnum::labels()" selected="{{ old('bidang_mafindo', $detail?->bidang_mafindo) }}" required />
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
                <label for="no-wa" class="form-label required">Nomor Whatsapp</label>
                <x-form.input name="no_wa" type="tel" x-mask="9999999999999" placeholder="08xxxxxxxxxx" value="{{ old('no_wa', $detail?->no_wa) }}" required />
              </div>
              <div class="col-12 col-md-6">
                <label for="no-hp" class="form-label">Nomor HP</label>
                <x-form.input name="no_hp" type="tel" x-mask="9999999999999" placeholder="08xxxxxxxxxx" value="{{ old('no_hp', $detail?->no_hp) }}" />
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="medsos-facebook" class="form-label">Akun Facebook</label>
                <x-form.input name="medsos[facebook]" type="text" placeholder="Nama Akun" value="{{ old('medsos.facebook', $detail?->medsos?->facebook) }}" />
              </div>
              <div class="col-12 col-md-6">
                <label for="medsos-instagram" class="form-label">Akun Instagram</label>
                <div class="input-group mb-2">
                  <span class="input-group-text">@</span>
                  <x-form.input name="medsos[instagram]" type="text" placeholder="username" value="{{ old('medsos.instagram', $detail?->medsos?->instagram) }}" />
                </div>
              </div>
            </div>
            <div class="row mb-3">
              <div class="col-12 col-md-6 mb-3 mb-md-0">
                <label for="medsos-tiktok" class="form-label">Akun Tiktok</label>
                <div class="input-group mb-2">
                  <span class="input-group-text">@</span>
                  <x-form.input name="medsos[tiktok]" type="text" placeholder="username" value="{{ old('medsos.tiktok', $detail?->medsos?->tiktok) }}" />
                </div>
              </div>
              <div class="col-12 col-md-6">
                <label for="medsos-twitter" class="form-label">Akun Twitter</label>
                <div class="input-group mb-2">
                  <span class="input-group-text">@</span>
                  <x-form.input name="medsos[twitter]" type="text" placeholder="username" value="{{ old('medsos.twitter', $detail?->medsos?->twitter) }}" />
                </div>
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
                <div>Tolong periksa kembali data yang Anda masukkan.</div>
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
                        <x-form.select id="pendidikan-tingkat" x-model="row.tingkat" x-bind:name="`pendidikan[${index}][tingkat]`" :showError=false required>
                          <option value="volvo">Volvo</option>
                          <option value="saab">Saab</option>
                          <option value="mercedes">Mercedes</option>
                          <option value="audi">Audi</option>
                        </x-form.select>
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
                        <label for="pendidikan">Jurusan</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 0">
                  <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" @click="del(index)">
                    <x-lucide-x class="icon text-red" style="max-width: unset" defer />
                  </div>
                </div>
              </div>
            </template>
            <button class="btn" type="button" @click="add()">
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
                <div>Tolong periksa kembali data yang Anda masukkan.</div>
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
                        <x-form.input id="pekerjaan-tahun" x-model="row.tahun" x-bind:name="`pekerjaan[${index}][tahun]`" type="text" x-mask="9999-9999" maxlength="255"
                          placeholder="Tahun" :showError=false required />
                        <label for="pekerjaan-tahun">Tahun (YYYY-YYYY)</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 0">
                  <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" @click="del(index)">
                    <x-lucide-x class="icon text-red" style="max-width: unset" defer />
                  </div>
                </div>
              </div>
            </template>
            <button class="btn" type="button" @click="add()">
              <x-lucide-plus class="icon" />
              Tambah
            </button>
          </div>
        </div>

        <div id="sertifikat" class="card card-mafindo">
          <div class="card-header">
            <h2 class="card-title">Sertifikat</h2>
          </div>
          <div class="card-body" x-data="pekerjaan">
            @if ($errors?->has('pekerjaan.*'))
              <x-alert class="alert-danger">
                <div>Tolong periksa kembali data yang Anda masukkan.</div>
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
                        <x-form.input id="sertifikat-masa" x-model="row.masa" x-bind:name="`sertifikat[${index}][masa]`" type="text" x-mask="9999-9999" maxlength="255"
                          placeholder="Masa Berlaku" :showError=false required />
                        <label for="sertifikat-masa">Masa Berlaku</label>
                      </div>
                    </div>
                  </div>
                </div>
                <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 0">
                  <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" @click="del(index)">
                    <x-lucide-x class="icon text-red" style="max-width: unset" defer />
                  </div>
                </div>
              </div>
            </template>
            <button class="btn" type="button" @click="add()">
              <x-lucide-plus class="icon" />
              Tambah
            </button>
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
    Alpine.data('pekerjaan', createDynamicList('sertifikat', dataSertifikat));

    Alpine.data('imgPreview', () => ({
      img: {{ Js::from(Auth::user()->foto ? Storage::url(Auth::user()->foto) : null) }},
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
  })
</script>
