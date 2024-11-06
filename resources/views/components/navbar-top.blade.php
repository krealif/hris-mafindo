<header class="navbar navbar-expand-md d-print-none navbar-top">
  <div class="container-xl">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar-menu" aria-controls="navbar-menu" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <!--Logo-->
    <h1 class="navbar-brand navbar-brand-autodark d-none-navbar-horizontal pe-0 pe-md-3">
      <a href="#">
        <img src="{{ asset('static/mafindo-logo.png') }}" height="40" alt="Tabler" class="navbar-brand-image">
      </a>
    </h1>
    <!--End Logo-->
    <div class="navbar-nav flex-row order-md-last">
      <div class="d-none d-md-flex">
        <!--Notification-->
        <div class="nav-item dropdown d-none d-md-flex me-3">
          <a href="#" class="nav-link px-0" data-bs-toggle="dropdown" tabindex="-1" aria-label="Show notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="icon" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none" /><path d="M10 5a2 2 0 1 1 4 0a7 7 0 0 1 4 6v3a4 4 0 0 0 2 3h-16a4 4 0 0 0 2 -3v-3a7 7 0 0 1 4 -6" /><path d="M9 17v1a3 3 0 0 0 6 0v-1" /></svg>
            <span class="badge bg-red"></span>
          </a>
          <div class="dropdown-menu dropdown-menu-arrow dropdown-menu-end dropdown-menu-card">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Pemberitahuan Terakhir</h3>
              </div>
              <div class="list-group list-group-flush list-group-hoverable">
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="status-dot status-dot-animated bg-red d-block"></span>
                    </div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Pengajuan Surat Baru!</a>
                      <div class="d-block text-secondary text-truncate mt-n1"> Relawan xx mengajukan surat baru </div>
                    </div>
                  </div>
                </div>
                <div class="list-group-item">
                  <div class="row align-items-center">
                    <div class="col-auto">
                      <span class="status-dot d-block"></span>
                    </div>
                    <div class="col text-truncate">
                      <a href="#" class="text-body d-block">Pengajuan Relawan Baru!</a>
                      <div class="d-block text-secondary text-truncate mt-n1"> Pengajuan pembuatan akun relawan baru </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
        <!--End Notification-->
      </div>
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
          <a href="#"class="dropdown-item">
            <svg class="icon dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#97a1b1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-user"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h4a4 4 0 0 1 4 4v2" /></svg>
            Profil
          </a>
          <form id="logout-form" action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" class="dropdown-item">
              <svg class="icon dropdown-item-icon" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="#97a1b1" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path stroke="none" d="M0 0h24v24H0z" fill="none"></path><path d="M14 8v-2a2 2 0 0 0 -2 -2h-7a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h7a2 2 0 0 0 2 -2v-2"></path><path d="M9 12h12l-3 -3"></path><path d="M18 15l3 -3"></path></svg>
              Keluar
            </button>
          </form>
        </div>
      </div>
    </div>
  </div>
</header>
