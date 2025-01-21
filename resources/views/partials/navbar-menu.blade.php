<div class="navbar-expand-md">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar">
      <div class="container-xl">
        <ul class="navbar-nav">
          <li @class(['nav-item', 'active' => request()->routeIs('home')])>
            <a class="nav-link" href="{{ route('home') }}">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <x-lucide-home class="icon" />
              </span>
              <span class="nav-link-title">Beranda</span>
            </a>
          </li>
          @role('admin')
            <li @class([
                'nav-item dropdown',
                'active' => request()->is('registrasi/*'),
            ])>
              <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-users class="icon" />
                </span>
                <span class="nav-link-title">Registrasi</span>
              </a>
              <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                  <div class="dropdown-menu-column">
                    <a href="{{ route('registrasi.index') }}" @class([
                        'dropdown-item',
                        'active' =>
                            request()->routeIs('registrasi.index') ||
                            request()->routeIs('registrasi.show'),
                    ])>Proses Registrasi</a>
                    <a href="{{ route('registrasi.indexLog') }}" @class([
                        'dropdown-item',
                        'active' => request()->routeIs('registrasi.indexLog'),
                    ])>Log Permohonan</a>
                    <a href="{{ route('migrasi.index') }}" @class([
                        'dropdown-item',
                        'active' =>
                            request()->routeIs('migrasi.index') ||
                            request()->is('registrasi/migrasi/*'),
                    ])>Migrasi Data</a>
                  </div>
                </div>
              </div>
            </li>
          @endrole
          @role(['admin', 'pengurus-wilayah'])
            <li @class(['nav-item dropdown', 'active' => request()->is('surat/*')])>
              <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-file-text class="icon" />
                </span>
                <span class="nav-link-title">Surat</span>
              </a>
              <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                  @role('pengurus-wilayah')
                    <div class="dropdown-menu-column">
                      <a href="{{ route('surat.letterbox') }}" @class([
                          'dropdown-item',
                          'active' => request()->routeIs('surat.letterbox'),
                      ])>Kotak Surat</a>
                      <a href="{{ route('surat.indexWilayah') }}" @class([
                          'dropdown-item',
                          'active' => request()->routeIs('surat.indexWilayah'),
                      ])>Permohonan Wilayah</a>
                    </div>
                  @else
                    <div class="dropdown-menu-column">
                      <a href="{{ route('surat.index') }}" @class([
                          'dropdown-item',
                          'active' => request()->routeIs('surat.index'),
                      ])>Proses Permohonan</a>
                      <a href="{{ route('surat.indexHistory') }}" @class([
                          'dropdown-item',
                          'active' => request()->routeIs('surat.indexHistory'),
                      ])>Histori Permohonan</a>
                    </div>
                  @endrole
                </div>
              </div>
            </li>
          @else
            <li @class(['nav-item', 'active' => request()->is('surat/*')])>
              <a class="nav-link" href="{{ route('surat.letterbox') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-file-text class="icon" />
                </span>
                <span class="nav-link-title">Surat</span>
              </a>
            </li>
          @endrole
          @can('viewAny', App\Models\Event::class)
            <li @class([
                'nav-item',
                'active' =>
                    request()->routeIs('kegiatan.index') || request()->is('kegiatan/*'),
            ])>
              <a class="nav-link" href="{{ Auth::user()->hasPermissionTo('join-event') ? route('kegiatan.indexJoined') : route('kegiatan.index') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-calendar-days class="icon" />
                </span>
                <span class="nav-link-title">Kegiatan</span>
              </a>
            </li>
          @endcan

          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <x-lucide-book-text class="icon" />
              </span>
              <span class="nav-link-title">Materi</span>
            </a>
          </li>

          @role('admin')
            <li @class(['nav-item dropdown', 'active' => request()->is('data/*')])>
              <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-database class="icon" />
                </span>
                <span class="nav-link-title">Data</span>
              </a>
              <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                  <div class="dropdown-menu-column">
                    <a href="{{ route('user.index') }}" @class([
                        'dropdown-item',
                        'active' => request()->routeIs('user.index'),
                    ])>Pengguna</a>
                  </div>
                </div>
              </div>
            </li>
          @endrole
          @role('pengurus-wilayah')
            <li @class([
                'nav-item',
                'active' => request()->routeIs('user.indexWilayah'),
            ])>
              <a class="nav-link" href="{{ route('user.indexWilayah') }}">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-users class="icon" />
                </span>
                <span class="nav-link-title">Relawan</span>
              </a>
            </li>
          @endrole
        </ul>
      </div>
    </div>
  </div>
</div>
