@extends('layouts.dashboard', [
    'title' => "{$user->nama} | Permohonan Registrasi",
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="title-wrapper">
          <div>
            <a href="{{ url()->previous() == route('registrasi.indexLog') ? route('registrasi.indexLog') : route('registrasi.index') }}" class="btn btn-link px-0 py-1 mb-1">
              <x-lucide-arrow-left class="icon" />
              Kembali
            </a>
            <h1 class="page-title">
              Detail Permohonan
            </h1>
          </div>
          @can('delete', $registration)
            @if (url()->previous() == route('registrasi.indexLog'))
              <button data-bs-toggle="modal" data-bs-target="#modal-delete" class="btn" x-data="{ id: {{ $registration->id }} }" x-on:click="$dispatch('set-id', { id })">
                <x-lucide-trash-2 class="icon text-red" />
                Hapus
              </button>
            @endif
          @endcan
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div>
        @if (flash()->message)
          <x-alert type="{{ flash()->class }}">
            {{ flash()->message }}
          </x-alert>
        @endif
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
      </div>
      <div class="row g-3">
        <div class="col-12 col-md-7 col-lg-6 order-1">
          <div class="card card-mafindo">
            @if ($registration->type->value == 'relawan-baru')
              <x-registration-step current="{{ $registration->step }}" :steps="App\Enums\RegistrationBaruStepEnum::steps()" />
            @else
              <x-registration-step current="{{ $registration->step }}" :steps="App\Enums\RegistrationLamaStepEnum::steps()" />
            @endif
            <div class="card-body">
              <div class="row g-3">
                <div class="col-12 col-lg-auto">
                  <img src="{{ $user->foto ? Storage::url($user->foto) : asset('static/img/profile-placeholder.png') }}" class="avatar avatar-xl" />
                </div>
                <div class="col">
                  <h2 class="card-title h2 mb-2">{{ $user->nama }}</h2>
                  <h4 class="card-subtitle h3 mb-2 text-dark">
                    {{ $registration->type?->label() }}
                    @if ($user->branch_id)
                      {{ "({$user->branch?->name})" }}
                    @endif
                  </h4>
                  <x-badge class="fs-4" :case="$registration->status" />
                </div>
              </div>
              <div class="datagrid datagrid-h mt-3">
                <x-datagrid-item title="Mendaftar" content="{{ $registration->created_at?->translatedFormat('d F Y / H:i') }}" />
                <x-datagrid-item title="Diperbarui" content="{{ $registration->updated_at?->translatedFormat('d F Y / H:i') }}" />
              </div>
            </div>
            @if (in_array($registration->status->value, ['revisi', 'ditolak']))
              <div class="card-body bg-orange-lt text-dark">
                <h4 class="text-red text-uppercase m-0">Alasan {{ $registration?->status->value }}</h4>
                <p class="mt-2">{{ $registration->message }}</p>
              </div>
            @endif
            @if ($registration->status->value == 'diproses')
              <div class="card-body">
                <ul class="nav nav-pills gap-2" role="tablist">
                  @can('approve', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-selesai" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-check class="icon text-green me-2" />
                        Selesai
                      </a>
                    </li>
                  @endcan
                  @can('nextStep', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-lanjut" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-arrow-right-to-line class="icon text-blue me-2" />
                        Lanjut
                      </a>
                    </li>
                  @endcan
                  @can('requestRevision', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-revisi" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-file-pen-line class="icon text-orange me-2" />
                        Revisi
                      </a>
                    </li>
                  @endcan
                  @can('reject', $registration)
                    <li class="nav-item" role="presentation">
                      <a href="#tab-tolak" class="btn" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-x class="icon text-red me-2" />
                        Tolak
                      </a>
                    </li>
                  @endcan
                </ul>
              </div>
              <div class="tab-content">
                @can('approve', $registration)
                  <div id="tab-selesai" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.approve', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      @if ($registration->step->value == 'verifikasi')
                        <div class="mb-4">
                          <label for="no-relawan" class="form-label required">Nomor Kartu Relawan</label>
                          <x-form.input name="no_relawan" type="text" value="{{ old('no_relawan', $user->no_relawan) }}" required />
                        </div>
                      @elseif ($registration->step->value == 'pelatihan')
                        <h5 class="fs-4 m-0 mb-2">Perhatian!</h5>
                        <p class="mb-4">Pastikan relawan telah <strong>mengikuti Pelatihan Dasar Relawan</strong>. Setelah permohonan registrasi diselesaikan, relawan yang
                          bersangkutan
                          akan <strong>berubah menjadi Relawan Wilayah</strong>.</p>
                      @endif
                      <button class="btn btn-primary" type="submit">Selesaikan</button>
                    </form>
                  </div>
                @endcan
                @can('nextStep', $registration)
                  <div id="tab-lanjut" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.nextStep', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      @if ($registration->step->value == 'wawancara')
                        <div class="mb-4">
                          <label for="no-relawan" class="form-label required">Nomor Kartu Relawan</label>
                          <x-form.input name="no_relawan" type="text" value="{{ old('no_relawan') }}" required />
                        </div>
                      @endif
                      <button class="btn btn-primary" type="submit">Lanjutkan</button>
                    </form>
                  </div>
                @endcan
                @can('requestRevision', $registration)
                  <div id="tab-revisi" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.requestRevision', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <div class="mb-4">
                        <label for="message" class="form-label required">Alasan</label>
                        <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan revisi" required />
                      </div>
                      <button class="btn btn-primary" type="submit">Kirim</button>
                    </form>
                  </div>
                @endcan
                @can('reject', $registration)
                  <div id="tab-tolak" class="tab-pane">
                    <form method="POST" action="{{ route('registrasi.reject', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @method('PATCH')
                      <div class="mb-4">
                        <label for="message" class="form-label required">Pesan</label>
                        <x-form.textarea name="message" rows="5" required />
                      </div>
                      <button class="btn btn-danger" type="submit">Tolak</button>
                    </form>
                  </div>
                @endcan
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-5 col-lg-6 order-3 order-md-2">
          <div class="vstack gap-3">
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Informasi Pribadi</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Nama Lengkap" content="{{ $user->nama }}" />
                  <x-datagrid-item title="Nama Panggilan" content="{{ $user->detail->panggilan }}" />
                  <x-datagrid-item title="Email" content="{{ $user->email }}" />
                  <x-datagrid-item title="Tanggal Lahir"
                    content="{{ $user->detail->tgl_lahir?->translatedFormat('d F Y') }} ({{ floor($user->detail->tgl_lahir?->diffInYears()) }} Tahun)" />
                  <x-datagrid-item title="Jenis Kelamin" content="{{ $user->detail->gender?->label() }}" />
                  <x-datagrid-item title="Agama" content="{{ $user->detail->agama?->label() }}" />
                  <x-datagrid-item title="Alamat Domisili Saat Ini" content="{{ $user->detail->alamat }}" />
                  <x-datagrid-item title="Disabilitas" content="{{ $user->detail->disabilitas?->label() }}" />
                </div>
              </div>
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Keanggotaan di Mafindo</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Tahun Bergabung" content="{{ $user->detail->thn_bergabung }}" />
                  <x-datagrid-item title="Wilayah" content="{{ $user->branch?->name }}" />
                  <x-datagrid-item title="Keikutsertaan PDR" content="{{ $user->detail->pdr }}" />
                  <x-datagrid-item title="Nomor Kartu Relawan" content="{{ $user->no_relawan }}" />
                </div>
              </div>
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Kontak</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Nomor Whatsapp" content="{{ $user->detail->no_wa }}" />
                  <x-datagrid-item title="Nomor HP" content="{{ $user->detail->no_wa }}" />
                  <x-datagrid-item title="Akun Facebook" content="{{ $user->detail->medsos->facebook }}" />
                  <x-datagrid-item title="Akun Instagram" content="{{ $user->detail->medsos->instagram }}" />
                  <x-datagrid-item title="Akun Tiktok" content="{{ $user->detail->medsos->tiktok }}" />
                  <x-datagrid-item title="Akun Twitter" content="{{ $user->detail->medsos->twitter }}" />
                </div>
              </div>
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Bidang</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Bidang Keahlian" content="{{ $user->detail->bidang_keahlian }}" />
                  <x-datagrid-item title="Bidang yang Ingin Dikembangkan" content="{{ $user->detail->bidang_mafindo?->label() }}" />
                </div>
              </div>
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Riwayat Pendidikan</h3>
              </div>
              @if ($listPendidikan = $user->detail->pendidikan)
                <table class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th scope="col">Tingkat</th>
                      <th scope="col">Institusi</th>
                      <th scope="col">Jurusan/Prodi</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($listPendidikan as $pendidikan)
                      <tr>
                        <td>{{ $pendidikan->tingkat }}</td>
                        <td>{{ $pendidikan->institusi }}</td>
                        <td>{{ $pendidikan->jurusan }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @else
                <div class="card-body">
                  -
                </div>
              @endif
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Riwayat Pekerjaan</h3>
              </div>
              @if ($listPekerjaan = $user->detail->pekerjaan)
                <table class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th scope="col">Jabatan</th>
                      <th scope="col">Lembaga</th>
                      <th scope="col">Tahun</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($listPekerjaan as $pekerjaan)
                      <tr>
                        <td>{{ $pekerjaan->jabatan }}</td>
                        <td>{{ $pekerjaan->lembaga }}</td>
                        <td>{{ $pekerjaan->tahun }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @else
                <div class="card-body">
                  -
                </div>
              @endif
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Sertifikat</h3>
              </div>
              @if ($listSertifikat = $user->detail->sertifikat)
                <table class="table table-vcenter card-table">
                  <thead>
                    <tr>
                      <th scope="col">Nama</th>
                      <th scope="col">Masa</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($listSertifikat as $sertifikat)
                      <tr>
                        <td>{{ $sertifikat->nama }}</td>
                        <td>{{ $sertifikat->masa }}</td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
              @else
                <div class="card-body">
                  -
                </div>
              @endif
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  @can('delete', $registration)
    @if (url()->previous() == route('registrasi.indexLog'))
      <x-modal-delete baseUrl="{{ route('registrasi.index') }}" />
    @endif
  @endcan
@endsection
