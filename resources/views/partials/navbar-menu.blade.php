<div class="navbar-expand-md">
  <div class="collapse navbar-collapse" id="navbar-menu">
    <div class="navbar">
      <div class="container-xl">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" href="#">
              <span class="nav-link-icon d-md-none d-lg-inline-block">
                <x-lucide-home class="icon" />
              </span>
              <span class="nav-link-title">Beranda</span>
            </a>
          </li>
          @role('admin')
            <li class="nav-item dropdown">
              <a class="nav-link dropdown-toggle" href="#navbar-base" data-bs-toggle="dropdown" data-bs-auto-close="outside" role="button" aria-expanded="false">
                <span class="nav-link-icon d-md-none d-lg-inline-block">
                  <x-lucide-users class="icon" />
                </span>
                <span class="nav-link-title">Registrasi</span>
              </a>
              <div class="dropdown-menu">
                <div class="dropdown-menu-columns">
                  <div class="dropdown-menu-column">
                    <a class="dropdown-item" href="{{ route('verif.indexRelawan') }}">Relawan</a>
                    <a class="dropdown-item" href="{{ route('verif.indexPengurus') }}">Pengurus</a>
                    <a class="dropdown-item" href="">Data</a>
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
              <span class="nav-link-title">Persuratan</span>
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
