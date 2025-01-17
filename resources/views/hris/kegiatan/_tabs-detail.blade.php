<ul class="nav nav-bordered panel-tabs mb-4" style="margin-top: -.25rem">
  <li class="nav-item">
    <a href="{{ route('kegiatan.show', $event->id) }}" @class([
        'nav-link',
        'active' => request()->routeIs('kegiatan.show', $event->id),
    ])>
      <x-lucide-text class="icon me-2" />
      Info
    </a>
  </li>
  <li class="nav-item">
    <a href="{{ route('kegiatan.showParticipant', $event->id) }}" @class([
        'nav-link',
        'active' => request()->routeIs('kegiatan.showParticipant', $event->id),
    ])>
      <x-lucide-users class="icon me-2" />
      Peserta
    </a>
  </li>
  @can('manageCertificate', $event)
    <li class="nav-item">
      <a href="{{ route('sertifikat.index', $event->id) }}" @class([
          'nav-link',
          'active' => request()->routeIs('sertifikat.index', $event->id),
      ])>
        <x-lucide-award class="icon me-2" />
        Sertifikat
      </a>
    </li>
  @endcan
</ul>
