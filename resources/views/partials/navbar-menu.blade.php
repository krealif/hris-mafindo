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
                    ])>Ajuan Registrasi</a>
                    <a href="{{ route('registrasi.history') }}" @class([
                        'dropdown-item',
                        'active' => request()->routeIs('registrasi.history'),
                    ])>Histori Registrasi</a>
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
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <x-lucide-file-text class="icon" />
              </span>
              <span class="nav-link-title">Surat</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <x-lucide-calendar-days class="icon" />
              </span>
              <span class="nav-link-title">Kegiatan</span>
            </a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <x-lucide-book-text class="icon" />
              </span>
              <span class="nav-link-title">Materi</span>
            </a>
          </li>
        </ul>
      </div>
    </div>
  </div>
</div>
