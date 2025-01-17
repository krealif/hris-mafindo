<div class="navbar navbar-expand-md d-print-none bg-primary">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!--Logo-->
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="/">
        <img src="{{ asset('static/img/mafindo-logo.png') }}" height="40" alt="Mafindo" class="navbar-brand-image">
      </a>
    </h1>
    <!--End Logo-->
    <div class="navbar-nav flex-row order-md-last">
      <!--Profile-->
      @if (auth()->user()->is_approved)
        <div class="nav-item dropdown">
          <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Buka menu akun">
            <img src="{{ auth()->user()->foto ? Storage::url(auth()->user()->foto) : asset('static/img/profile-placeholder.png') }}" class="avatar avatar-sm">
            <div class="d-none d-xl-block ps-2 " style="max-width: 96px">
              <span class="text-white fw-medium text-truncate">{{ explode(' ', auth()->user()->nama)[0] }}</span>
            </div>
          </a>
          <div class="dropdown-menu dropdown-menu-end dropdown-menu-arrow" style="width: 200px;">
            <div class="px-3 py-2">
              <span class="d-block text-truncate">{{ auth()->user()->nama }}</span>
              <span class="d-block text-muted text-truncate">{{ auth()->user()->email }}</span>
            </div>
            <div class="dropdown-divider"></div>
            <a href="{{ route('user.profile') }}" @class([
                'dropdown-item',
                'active' => request()->routeIs('user.profile') || request()->is('profil/*'),
            ])>
              <x-lucide-user class="icon dropdown-item-icon" />
              Profil
            </a>
            <form action="{{ route('logout') }}" method="POST">
              @csrf
              <button type="submit" class="dropdown-item">
                <x-lucide-log-out class="icon dropdown-item-icon" defer />
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
