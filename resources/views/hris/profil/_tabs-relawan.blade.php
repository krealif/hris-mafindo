<ul class="nav nav-bordered panel-tabs mb-4">
  <li class="nav-item">
    <a href="{{ route('user.profile') }}" @class(['nav-link', 'active' => request()->routeIs('user.profile')])>
      <x-lucide-users class="icon me-2" />
      Profil
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('user.relawanCertificate') }}" @class([
        'nav-link',
        'active' => request()->routeIs('user.relawanCertificate'),
    ])>
      <x-lucide-award class="icon me-2" />
      Sertifikat
    </a>
  </li>
</ul>
