<ul class="nav nav-bordered panel-tabs mb-4">
  <li class="nav-item">
    <a href="{{ route('user.profile') }}" @class(['nav-link', 'active' => request()->routeIs('user.profile')])>
      <x-lucide-users class="icon me-2" />
      Profil
    </a>
  </li>
</ul>
