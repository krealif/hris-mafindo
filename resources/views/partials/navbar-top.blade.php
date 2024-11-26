<div class="navbar navbar-expand-md d-print-none bg-primary">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!--Logo-->
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="/">
        <img src="{{ asset('static/mafindo-logo.png') }}" height="40" alt="Tabler" class="navbar-brand-image">
      </a>
    </h1>
    <!--End Logo-->
    <div class="navbar-nav flex-row order-md-last">
      <!--Profile-->
      @if (auth()->user()->is_verified)
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Buka menu akun">
            <span class="avatar avatar-sm">
              <span class="avatar avatar-sm"></span>
            </span>
            <div class="d-none d-xl-block ps-2">
              <div class="text-white fw-medium">{{ explode(' ', auth()->user()->name)[0] }}</div>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow">
            <div class="px-3 py-2">
              <span class="d-block">{{ auth()->user()->name }}</span>
              <span class="d-block text-muted">{{ auth()->user()->email }}</span>
            </div>
            <div class="dropdown-divider"></div>
            <a href="#" class="dropdown-item">
              <x-lucide-user class="icon" />
              Profil
            </a>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item">
                <x-lucide-log-out class="icon" defer />
                Keluar
              </button>
            </form>
          </div>
        </div>
      @else
        <form action="{{ route('logout') }}" method="POST" class="nav-item">
          @csrf
          <button type="submit" class="btn btn-light">
            <x-lucide-log-out class="icon text-red" defer />
            Keluar
          </button>
        </form>
      @endif
    </div>
  </div>
</div>
