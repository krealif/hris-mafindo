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
      <div class="nav-item dropdown">
        <a href="#" class="nav-link d-flex lh-1 text-reset p-0" data-bs-toggle="dropdown" aria-label="Buka menu akun">
          <span class="avatar avatar-sm">
            <span class="avatar avatar-sm" style="background-image: url(https://preview.tabler.io/static/avatars/000m.jpg)"></span>
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
            <svg class="icon dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M19 21v-2a4 4 0 0 0-4-4H9a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            Profil
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">
              <svg class="icon dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M9 21H5a2 2 0 0 1-2-2V5a2 2 0 0 1 2-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" x2="9" y1="12" y2="12"/></svg>
              Keluar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
