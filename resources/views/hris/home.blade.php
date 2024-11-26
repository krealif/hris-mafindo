@extends('layouts.dashboard', [
  'title' => 'Dashboard'
])

@section('content')
<div class="page-wrapper">
    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h1 class="page-title">
                  Dashboard
                </h1>
              </div>
            </div>
        </div>
    </div>
    <!--Page Body-->
    <div class="page-body" id="dash-admin">
      <div class="container-xl">
        <div class="card card-lg">
          <div class="card-body">
            <div class="row row-cards">
              <div class="col-sm-6 col-lg-3">
                <a href="" class="card card-link card-link-pop btn-sec-yellow">
                  <div class="empty">
                    <div class="empty-img">
                      <svg xmlns="http://www.w3.org/2000/svg" width="80" height="80" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" class="icon icon-tabler icons-tabler-outline icon-tabler-users-plus">
                        <path stroke="none" d="M0 0h24v24H0z" fill="none" />
                        <path d="M5 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" />
                        <path d="M3 21v-2a4 4 0 0 1 4 -4h4c.96 0 1.84 .338 2.53 .901" />
                        <path d="M16 3.13a4 4 0 0 1 0 7.75" />
                        <path d="M16 19h6" />
                        <path d="M19 16v6" />
                      </svg>
                    </div>
                    <h3 class="empty-title">Pendaftaran</h3>
                  </div>
                </a>
              </div>

              <div class="col-sm-6 col-lg-3">
                <a href="./data-relawan" class="card card-link card-link-pop btn-sec-yellow">
                  <div class="empty">
                    <div class="empty-img">
                      <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-user-search"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M8 7a4 4 0 1 0 8 0a4 4 0 0 0 -8 0" /><path d="M6 21v-2a4 4 0 0 1 4 -4h1.5" /><path d="M18 18m-3 0a3 3 0 1 0 6 0a3 3 0 1 0 -6 0" /><path d="M20.2 20.2l1.8 1.8" /></svg>
                    </div>
                    <h3 class="empty-title">Riwayat Relawan</h3>
                  </div>
                </a>
              </div>

              <div class="col-sm-6 col-lg-3">
                <a href="./add-kegiatan" class="card card-link card-link-pop btn-sec-yellow">
                  <div class="empty">
                    <div class="empty-img">
                      <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-calendar-clock"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M10.5 21h-4.5a2 2 0 0 1 -2 -2v-12a2 2 0 0 1 2 -2h12a2 2 0 0 1 2 2v3" /><path d="M16 3v4" /><path d="M8 3v4" /><path d="M4 11h10" /><path d="M18 18m-4 0a4 4 0 1 0 8 0a4 4 0 1 0 -8 0" /><path d="M18 16.5v1.5l.5 .5" /></svg>
                    </div>
                    <h3 class="empty-title">Kegiatan Baru</h3>
                  </div>
                </a>
              </div>
              <div class="col-sm-6 col-lg-3">
                <a href="./add-materi" class="card card-link card-link-pop btn-sec-yellow">
                  <div class="empty">
                    <div class="empty-img">
                      <svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="none"  stroke="currentColor"  stroke-width="2"  stroke-linecap="round"  stroke-linejoin="round"  class="icon icon-tabler icons-tabler-outline icon-tabler-clipboard-plus"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9 5h-2a2 2 0 0 0 -2 2v12a2 2 0 0 0 2 2h10a2 2 0 0 0 2 -2v-12a2 2 0 0 0 -2 -2h-2" /><path d="M9 3m0 2a2 2 0 0 1 2 -2h2a2 2 0 0 1 2 2v0a2 2 0 0 1 -2 2h-2a2 2 0 0 1 -2 -2z" /><path d="M10 14h4" /><path d="M12 12v4" /></svg>
                    </div>
                    <h3 class="empty-title">Materi Baru</h3>
                  </div>
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Page header -->
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row g-2 align-items-center">
              <div class="col">
                <h1 class="page-title">
                  Pemberitahuan
                </h1>
              </div>
            </div>
        </div>
    </div>

    <div class="page-body">
      <div class="container-xl">
        <div class="row justify-content-center">
          <div class="col-12">
            <div class="card">
              <div class="card-body">
                <div class="divide-y">
                  <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.63 17.531c.612 .611 .211 1.658 -.652 1.706a3.992 3.992 0 0 1 -3.05 -1.166a3.992 3.992 0 0 1 -1.165 -3.049c.046 -.826 1.005 -1.228 1.624 -.726l.082 .074l3.161 3.16z" /><path d="M20.071 3.929c.96 .96 1.134 2.41 .52 3.547l-.09 .153l-.024 .036a8.013 8.013 0 0 1 -1.446 7.137l-.183 .223l-.191 .218l-2.073 2.072l-.08 .112a3 3 0 0 0 -.499 2.113l.035 .201l.045 .185c.264 .952 -.853 1.645 -1.585 1.051l-.086 -.078l-11.313 -11.313c-.727 -.727 -.017 -1.945 .973 -1.671a3 3 0 0 0 2.5 -.418l.116 -.086l2.101 -2.1a8 8 0 0 1 7.265 -1.86l.278 .071l.037 -.023a3.003 3.003 0 0 1 3.432 .192l.14 .117l.128 .12z" /></svg></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                          <strong>Relawan XX</strong> mengajukan surat baru!
                        </div>
                        <div class="text-secondary">yesterday</div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.63 17.531c.612 .611 .211 1.658 -.652 1.706a3.992 3.992 0 0 1 -3.05 -1.166a3.992 3.992 0 0 1 -1.165 -3.049c.046 -.826 1.005 -1.228 1.624 -.726l.082 .074l3.161 3.16z" /><path d="M20.071 3.929c.96 .96 1.134 2.41 .52 3.547l-.09 .153l-.024 .036a8.013 8.013 0 0 1 -1.446 7.137l-.183 .223l-.191 .218l-2.073 2.072l-.08 .112a3 3 0 0 0 -.499 2.113l.035 .201l.045 .185c.264 .952 -.853 1.645 -1.585 1.051l-.086 -.078l-11.313 -11.313c-.727 -.727 -.017 -1.945 .973 -1.671a3 3 0 0 0 2.5 -.418l.116 -.086l2.101 -2.1a8 8 0 0 1 7.265 -1.86l.278 .071l.037 -.023a3.003 3.003 0 0 1 3.432 .192l.14 .117l.128 .12z" /></svg></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                          <strong>Relawan XX</strong> mengajukan surat baru!
                        </div>
                        <div class="text-secondary">yesterday</div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.63 17.531c.612 .611 .211 1.658 -.652 1.706a3.992 3.992 0 0 1 -3.05 -1.166a3.992 3.992 0 0 1 -1.165 -3.049c.046 -.826 1.005 -1.228 1.624 -.726l.082 .074l3.161 3.16z" /><path d="M20.071 3.929c.96 .96 1.134 2.41 .52 3.547l-.09 .153l-.024 .036a8.013 8.013 0 0 1 -1.446 7.137l-.183 .223l-.191 .218l-2.073 2.072l-.08 .112a3 3 0 0 0 -.499 2.113l.035 .201l.045 .185c.264 .952 -.853 1.645 -1.585 1.051l-.086 -.078l-11.313 -11.313c-.727 -.727 -.017 -1.945 .973 -1.671a3 3 0 0 0 2.5 -.418l.116 -.086l2.101 -2.1a8 8 0 0 1 7.265 -1.86l.278 .071l.037 -.023a3.003 3.003 0 0 1 3.432 .192l.14 .117l.128 .12z" /></svg></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                          <strong>Relawan XX</strong> mengajukan surat baru!
                        </div>
                        <div class="text-secondary">yesterday</div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.63 17.531c.612 .611 .211 1.658 -.652 1.706a3.992 3.992 0 0 1 -3.05 -1.166a3.992 3.992 0 0 1 -1.165 -3.049c.046 -.826 1.005 -1.228 1.624 -.726l.082 .074l3.161 3.16z" /><path d="M20.071 3.929c.96 .96 1.134 2.41 .52 3.547l-.09 .153l-.024 .036a8.013 8.013 0 0 1 -1.446 7.137l-.183 .223l-.191 .218l-2.073 2.072l-.08 .112a3 3 0 0 0 -.499 2.113l.035 .201l.045 .185c.264 .952 -.853 1.645 -1.585 1.051l-.086 -.078l-11.313 -11.313c-.727 -.727 -.017 -1.945 .973 -1.671a3 3 0 0 0 2.5 -.418l.116 -.086l2.101 -2.1a8 8 0 0 1 7.265 -1.86l.278 .071l.037 -.023a3.003 3.003 0 0 1 3.432 .192l.14 .117l.128 .12z" /></svg></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                          <strong>Relawan XX</strong> mengajukan surat baru!
                        </div>
                        <div class="text-secondary">yesterday</div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.63 17.531c.612 .611 .211 1.658 -.652 1.706a3.992 3.992 0 0 1 -3.05 -1.166a3.992 3.992 0 0 1 -1.165 -3.049c.046 -.826 1.005 -1.228 1.624 -.726l.082 .074l3.161 3.16z" /><path d="M20.071 3.929c.96 .96 1.134 2.41 .52 3.547l-.09 .153l-.024 .036a8.013 8.013 0 0 1 -1.446 7.137l-.183 .223l-.191 .218l-2.073 2.072l-.08 .112a3 3 0 0 0 -.499 2.113l.035 .201l.045 .185c.264 .952 -.853 1.645 -1.585 1.051l-.086 -.078l-11.313 -11.313c-.727 -.727 -.017 -1.945 .973 -1.671a3 3 0 0 0 2.5 -.418l.116 -.086l2.101 -2.1a8 8 0 0 1 7.265 -1.86l.278 .071l.037 -.023a3.003 3.003 0 0 1 3.432 .192l.14 .117l.128 .12z" /></svg></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                          <strong>Relawan XX</strong> mengajukan surat baru!
                        </div>
                        <div class="text-secondary">yesterday</div>
                      </div>
                    </div>
                  </div>
                  <div>
                    <div class="row">
                      <div class="col-auto">
                        <span class="avatar"><svg  xmlns="http://www.w3.org/2000/svg"  width="24"  height="24"  viewBox="0 0 24 24"  fill="currentColor"  class="icon icon-tabler icons-tabler-filled icon-tabler-bell-ringing-2"><path stroke="none" d="M0 0h24v24H0z" fill="none"/><path d="M9.63 17.531c.612 .611 .211 1.658 -.652 1.706a3.992 3.992 0 0 1 -3.05 -1.166a3.992 3.992 0 0 1 -1.165 -3.049c.046 -.826 1.005 -1.228 1.624 -.726l.082 .074l3.161 3.16z" /><path d="M20.071 3.929c.96 .96 1.134 2.41 .52 3.547l-.09 .153l-.024 .036a8.013 8.013 0 0 1 -1.446 7.137l-.183 .223l-.191 .218l-2.073 2.072l-.08 .112a3 3 0 0 0 -.499 2.113l.035 .201l.045 .185c.264 .952 -.853 1.645 -1.585 1.051l-.086 -.078l-11.313 -11.313c-.727 -.727 -.017 -1.945 .973 -1.671a3 3 0 0 0 2.5 -.418l.116 -.086l2.101 -2.1a8 8 0 0 1 7.265 -1.86l.278 .071l.037 -.023a3.003 3.003 0 0 1 3.432 .192l.14 .117l.128 .12z" /></svg></span>
                      </div>
                      <div class="col">
                        <div class="text-truncate">
                          <strong>Relawan XX</strong> mengajukan surat baru!
                        </div>
                        <div class="text-secondary">yesterday</div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
</div>
@endsection
