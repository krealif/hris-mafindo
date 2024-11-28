<div class="row g-3">
  <div class="col-12">
    <div class="card card-mafindo border-0">
      <div class="card-header border border-bottom-0" style="border-color: var(--tblr-border-color) !important">
        <h3 class="d-flex align-items-center gap-2 mb-0">
          <x-lucide-chevrons-right class="icon" />
          Tahapan
          <span class="badge bg-secondary text-white">Sedang diproses</span>
        </h3>
      </div>
      <ol class="stepper fw-bold">
        <li class="stepper-item">
          Isi Form Registrasi
        </li>
        <li class="stepper-item danger">
          Profiling Medsos
        </li>
        <li class="stepper-item">
          Wawancara
        </li>
        <li class="stepper-item">
          Terhubung Wilayah
        </li>
        <li class="stepper-item">
          Pelatihan Dasar Relawan
        </li>
      </ol>
    </div>
  </div>
  <div class="col-12 col-md-3 mb-3 mb-md-0">
    <div class="card card-mafindo sticky-top">
      <div class="card-header">
        <h3 class="card-title d-flex align-items-center gap-2">
          <x-lucide-list class="icon" />
          Daftar Isi
        </h3>
      </div>
      <nav id="daftar-isi" class="list-group list-group-flush">
        <a class="list-group-item list-group-item-action p-2" href="#informasi-pribadi">
          <x-lucide-arrow-down-right class="icon" defer />
          Informasi Pribadi
        </a>
        <a class="list-group-item list-group-item-action p-2" href="#foto-profil">
          <x-lucide-arrow-down-right class="icon" defer />
          Foto Profil
        </a>
        <a class="list-group-item list-group-item-action p-2" href="#foto-profil">
          <x-lucide-arrow-down-right class="icon" defer />
          Kontak
        </a>
        <a class="list-group-item list-group-item-action p-2" href="#latar-belakang">
          <x-lucide-arrow-down-right class="icon" defer />
          Latar Belakang
        </a>
      </nav>
    </div>
  </div>
  <div class="col-12 col-md-9">
    <form method="POST" class="vstack gap-2" x-data="{ isDraft: false }" x-bind:novalidate="isDraft" >
      @csrf
      <div id="informasi-pribadi" class="card card-mafindo">
        <div class="card-header">
          <h2 class="card-title">Informasi Pribadi</h2>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
              <label for="nama" class="form-label required">Nama Lengkap</label>
              <x-form.input name="nama" type="text" required />
            </div>
            <div class="col-12 col-md-6">
              <label for="panggilan" class="form-label required">Nama Panggilan</label>
              <x-form.input name="panggilan" type="text" required />
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
              <label for="tgl-lahir" class="form-label required">Tanggal Lahir</label>
              <x-form.input name="tgl_lahir" type="date" required />
            </div>
            <div class="col-12 col-md-6">
              <label for="gender" class="form-label required">Jenis Kelamin</label>
              <x-form.input name="gender" type="text" required />
            </div>
          </div>
          <div class="row mb-3">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
              <label for="agama" class="form-label required">Agama</label>
              <x-form.input name="agama" type="text" required />
            </div>
            <div class="col-12 col-md-6">
              <label for="alamat" class="form-label required">Alamat Domisili Saat Ini</label>
              <x-form.input name="alamat" type="text" required />
            </div>
          </div>
        </div>
      </div>

      {{-- <div id="foto-profil" class="card card-mafindo">
        <div class="card-header">
          <h2 class="card-title">Foto Profil</h2>
        </div>
        <div class="card-body" x-data="imgPreview">
          <div class="row">
            <div class="col-auto">
              <img id="frame" class="avatar avatar-xl" x-bind:src="newImg || 'https://static.canva.com/web/images/618bcd758dca3fa62a6a400aa25f6e9f.png'" />
            </div>
            <div class="col">
              <label for="photo" class="form-label required">Upload Foto</label>
              <div class="row g-2">
                <div class="col">
                  <x-form.input name="photo" type="file" x-ref="imgInput" @change="handleFileUpload" required />
                </div>
                <template x-if="newImg">
                  <div class="col-auto">
                    <button @click="cancelUpload" type="button" class="btn btn-icon">
                      <x-lucide-x class="icon text-red" />
                    </button>
                  </div>
                </template>
              </div>
            </div>
          </div>
        </div>
      </div> --}}

      <div id="kontak" class="card card-mafindo">
        <div class="card-header">
          <h2 class="card-title">Kontak</h2>
        </div>
        <div class="card-body">
          <div class="row mb-3">
            <div class="col-12 col-md-6 mb-3 mb-md-0">
              <label for="no-whatsapp" class="form-label required">Nomor Whatsapp</label>
              <x-form.input name="no_whatsapp" type="tel" required />
            </div>
            <div class="col-12 col-md-6">
              <label for="no-hp" class="form-label required">Nomor HP</label>
              <x-form.input name="no_hp" type="tel" required />
            </div>
          </div>
        </div>
      </div>

      <div id="riwayat-pendidikan" class="card card-mafindo">
        <div class="card-header">
          <h2 class="card-title">Riwayat Pendidikan</h2>
        </div>
        <div class="card-body" x-data="pendidikan">
          <template x-for="(row, index) in rows" :key="index">
            <div class="position-relative">
              <div class="row mb-3">
                <div class="col-12 col-md-2 mb-1 mb-md-0">
                  <div class="form-floating">
                    <div class="form-floating">
                      <x-form.select id="tingkat" x-model="row.tingkat" x-bind:name="`pendidikan[${index}][tingkat]`" required>
                        <option value="volvo">Volvo</option>
                        <option value="saab">Saab</option>
                        <option value="mercedes">Mercedes</option>
                        <option value="audi">Audi</option>
                      </x-form.select>
                      <label for="tingkat">Tingkat</label>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-5 mb-1 mb-md-0">
                  <div class="form-floating">
                    <div class="form-floating">
                      <x-form.input id="institusi" x-model="row.institusi" x-bind:name="`pendidikan[${index}][institusi]`" type="text" placeholder="Institusi" required />
                      <label for="institusi">Institusi</label>
                    </div>
                  </div>
                </div>
                <div class="col-12 col-md-5">
                  <div class="form-floating">
                    <div class="form-floating">
                      <x-form.input id="jurusan" x-model="row.jurusan" x-bind:name="`pendidikan[${index}][jurusan]`" type="text" placeholder="Institusi" required />
                      <label for="jurusan">Jurusan</label>
                    </div>
                  </div>
                </div>
              </div>
              <div class="position-absolute top-0 start-100 translate-middle me-1 mt-1" x-show="rows.length > 1">
                <div class="p-1 text-secondary border rounded-circle bg-white repeater-delete" class="bg-white" @click="del(index)">
                  <x-lucide-x class="icon text-red" style="max-width: unset" />
                </div>
              </div>
            </div>
          </template>
        <button class="btn" type="button" @click="add()">
          <x-lucide-plus class="icon" />
          Tambah
        </button>
      </div>
      <div class="card shadow">
        <input type="hidden" name="mode" x-bind:value="isDraft ? 'draft' : 'submit'">
        <div class="card-body">
          <button class="btn btn-primary" @click="isDraft = false">
            <x-lucide-send class="icon" />
            Ajukan
          </button>
          <button class="btn btn-secondary" @click="isDraft = true">
            <x-lucide-save class="icon" />
            Simpan Draft
          </button>
        </div>
      </div>
    </form>
  </div>
</div>

<script>
function createDynamicList(key, rowBase) {
  return () => ({
    key,
    rowBase,
    rows: [],

    // Actions
    add() {
      this.rows.push({ ...this.rowBase });
    },
    del(index) {
      this.rows.splice(index, 1);
    },
    init() {
      this.add();
    },
  });
}

document.addEventListener('alpine:init', () => {
  Alpine.data('pendidikan', createDynamicList('pendidikan', {
    tingkat: null,
    institusi: null,
    jurusan: null
  }));

  Alpine.data('imgPreview', () => ({
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
