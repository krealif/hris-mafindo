@php
  $currentUserId = request()->route('user')?->id;
@endphp

<ul class="nav nav-bordered panel-tabs mb-4">
  <li class="nav-item">
    <a href="{{ route('user.profile', request()->route('user')?->id) }}" @class([
        'nav-link',
        'active' => request()->routeIs(
            'user.profile',
            request()->route('user')?->id),
    ])>
      <x-lucide-users class="icon me-2" />
      Profil
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route($currentUserId ? 'user.certificateById' : 'user.certificate', $currentUserId) }}" @class([
        'nav-link',
        'active' => request()->routeIs(
            $currentUserId ? 'user.certificateById' : 'user.certificate'),
    ])>
      <x-lucide-award class="icon me-2" />
      Sertifikat
    </a>
  </li>
</ul>
