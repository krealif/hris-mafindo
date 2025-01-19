@extends('hris.pengguna.profil._main')

@section('profile-content')
  @include('hris.pengguna.profil._tabs-relawan')
  <div class="row row-deck row-cards g-3">
    <div class="col-12 col-md-6">
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
            <x-datagrid-item title="Disabilitas" content="{{ $user->detail->disabilitas }}" />
          </div>
        </div>
      </div>
    </div>
    <div class="col-12 col-md-6">
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
    </div>
    <div class="col-12 col-md-6">
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
    </div>
    <div class="col-12 col-md-6">
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
    </div>
    <div class="col-12 col-md-6">
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
    </div>
    <div class="col-12 col-md-6">
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
    </div>
    <div class="col-12 col-md-6">
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
@endsection
