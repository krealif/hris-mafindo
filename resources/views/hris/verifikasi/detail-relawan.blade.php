@extends('layouts.dashboard', [
    'title' => $registration->user->nama . ' | Detail Relawan',
])

@section('content')
  <div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
      <div class="container-xl">
        <div class="row g-2 align-items-center">
          <div class="col">
            <div class="mb-1">
              <x-breadcrumb>
                <x-breadcrumb-item label="Registrasi" route="verif.indexRelawan" />
              </x-breadcrumb>
            </div>
            <h1 class="page-title">
              Detail Relawan
            </h1>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
      <div class="row g-3">
        <div class="col-12">
          <div class="card card-mafindo overflow-hidden border-top-0">
            @if ($registration->type == 'relawan-baru')
              <x-registration-step :data="App\Enums\RegistrationBaruStepEnum::labels()" step="{{ $registration?->step }}" />
            @else
              <x-registration-step :data="App\Enums\RegistrationLamaStepEnum::labels()" step="{{ $registration?->step }}" />
            @endif
            <div class="card-body border-top">
              <div class="row g-3">
                <div class="col-auto">
                  <img src="{{ $user->foto ? Storage::url($user->foto) : '' }}" class="avatar avatar-xl" />
                </div>
                <div class="col">
                  <h2 class="card-title h2 mb-2">
                    {{ $user->nama }}
                  </h2>
                  <h4 class="card-subtitle h3 mb-2 text-muted">{{ $user->branch?->nama }}</h4>
                  <x-badge-enum class="fs-4 me-1" case="{{ $registration->type }}" :enumClass="App\Enums\RegistrationTypeEnum::class" />
                  <x-badge-enum class="fs-4" case="{{ $registration->status }}" :enumClass="App\Enums\RegistrationStatusEnum::class" />
                </div>
              </div>
            </div>
            @if ($registration?->status == 'revisi' && $registration->message)
              <div class="card-body border-top">
                <h4 class="fs-3 text-red">REVISI</h4>
                <p>{{ $registration->message }}</p>
              </div>
            @endif
          </div>
        </div>
        <div class="col-12 col-md-6">
          <div class="vstack gap-2">
            <div class="card card-mafindo">
              <div class="card-header">
                <h3 class="card-title">Informasi Pribadi</h3>
              </div>
              <div class="card-body">
                <div class="datagrid">
                  <x-datagrid-item title="Nama Lengkap" content="{{ $user->nama }}" />
                  <x-datagrid-item title="Nama Panggilan" content="{{ $user->detail->panggilan }}" />
                  <x-datagrid-item title="Email" content="{{ $user->email }}" />
                  <x-datagrid-item title="Tanggal Lahir" content="{{ $user->detail->tgl_lahir?->format('d/m/Y') }}" />
                  <x-datagrid-item title="Jenis Kelamin" content="{{ $user->detail->gender?->label() }}" />
                  <x-datagrid-item title="Agama" content="{{ $user->detail->agama?->label() }}" />
                  <x-datagrid-item title="Alamat Domisili Saat Ini" content="{{ $user->detail->alamat }}" />
                  <x-datagrid-item title="Disabilitas" content="{{ $user->detail->disabilitas }}" />
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
                  <x-datagrid-item title="Akun Facebook" content="{{ $user->detail->medsos?->facebook }}" />
                  <x-datagrid-item title="Akun Instagram" content="{{ $user->detail->medsos?->instagram }}" />
                  <x-datagrid-item title="Akun Tiktok" content="{{ $user->detail->medsos?->tiktok }}" />
                  <x-datagrid-item title="Akun Twitter" content="{{ $user->detail->medsos?->twitter }}" />
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
                  <x-datagrid-item title="Wilayah" content="{{ $user->branch?->nama }}" />
                  <x-datagrid-item title="Keikutsertaan PDR" content="{{ $user->detail->pdr }}" />
                  <x-datagrid-item title="Nomor Kartu Relawan" content="{{ $user->no_relawan }}" />
                </div>
              </div>
            </div>
            <div class="card card-mafindo">
              <div class="card-header">
                <h2 class="card-title">Bidang</h2>
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
                      <th scope="col">Jurusan</th>
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
                      <th scope="col">Jabatan</th>
                      <th scope="col">Lembaga</th>
                      <th scope="col">Tahun</th>
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
        @if ($registration->status == 'diproses')
          <div class="col-12 col-md-6">
            @if ($errors->any())
              <!-- Validation error -->
              <x-alert class="alert-danger">
                Error! Terjadi kesalahan saat mengirimkan form. Tolong periksa kembali data yang Anda masukkan.
              </x-alert>
            @endif
            <div class="card card-mafindo sticky-top">
              <div class="card-header">
                <h3 class="card-title">Aksi</h3>
              </div>
              <div class="card-body">
                <ul class="nav nav-pills gap-2" role="tablist">
                  @if (in_array($registration->step, ['pelatihan', 'verifikasi']))
                    <li class="nav-item" role="presentation">
                      <a href="#tab-selesai" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-circle-check class="icon text-green me-2" />
                        Selesai
                      </a>
                    </li>
                  @endif
                  @if (in_array($registration->step, ['profiling', 'wawancara', 'terhubung']))
                    <li class="nav-item" role="presentation">
                      <a href="#tab-lanjut" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-arrow-right-to-line class="icon text-blue me-2" />
                        Lanjut
                      </a>
                    </li>
                  @endif
                  @if (in_array($registration->step, ['profiling', 'verifikasi']))
                    <li class="nav-item" role="presentation">
                      <a href="#tab-revisi" class="btn fs-3" data-bs-toggle="tab" aria-selected="true" role="tab">
                        <x-lucide-file-pen-line class="icon text-orange me-2" />
                        Revisi
                      </a>
                    </li>
                  @endif
                </ul>
              </div>
              <div class="tab-content">
                @if (in_array($registration->step, ['pelatihan', 'verifikasi']))
                  <div id="tab-selesai" class="tab-pane">
                    <form method="POST" action="{{ route('verif.finish', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @if ($registration->step == 'verifikasi')
                        <div class="mb-4">
                          <label for="no-relawan" class="form-label required">Nomor Kartu Relawan</label>
                          <x-form.input name="no_relawan" type="text" value="{{ old('no_relawan', $user->no_relawan) }}" required />
                        </div>
                      @endif
                      <button class="btn btn-primary" type="submit">Selesaikan Registrasi</button>
                    </form>
                  </div>
                @endif
                @if (in_array($registration->step, ['profiling', 'wawancara', 'terhubung']))
                  <div id="tab-lanjut" class="tab-pane">
                    <form method="POST" action="{{ route('verif.nextStep', $registration->id) }}" class="card-body border-top">
                      @csrf
                      @if ($registration->step == 'wawancara')
                        <div class="mb-4">
                          <label for="no-relawan" class="form-label required">Nomor Kartu Relawan</label>
                          <x-form.input name="no_relawan" type="text" value="{{ old('no_relawan') }}" required />
                        </div>
                      @endif
                      <button class="btn btn-primary" type="submit">Lanjut Tahap Berikutnya</button>
                    </form>
                  </div>
                @endif
                @if (in_array($registration->step, ['profiling', 'verifikasi']))
                  <div id="tab-revisi" class="tab-pane">
                    <form method="POST" action="{{ route('verif.revisi', $registration->id) }}" class="card-body border-top">
                      @csrf
                      <div class="mb-4">
                        <label for="message" class="form-label required">Alasan</label>
                        <x-form.textarea name="message" rows="5" placeholder="Tuliskan alasan revisi" :showError=false required />
                      </div>
                      <button class="btn btn-primary" type="submit">Kirim</button>
                    </form>
                  </div>
                @endif
              </div>
            </div>
          </div>
        @endif
      </div>
    </div>
  </div>
@endsection

@push('scripts')
  <script></script>
@endpush
