<ul class="nav nav-bordered panel-tabs mb-4" style="margin-top: -.25rem">
  @haspermission('join-event')
    <li class="nav-item">
      <a href="{{ route('kegiatan.indexJoined') }}" @class([
          'nav-link',
          'active' => request()->routeIs('kegiatan.indexJoined'),
      ])>
        <x-lucide-calendar-plus class="icon me-2" />
        Mengikuti
      </a>
    </li>
    <li class="nav-item">
      <a href="{{ route('kegiatan.index') }}" @class(['nav-link', 'active' => request()->routeIs('kegiatan.index')])>
        <x-lucide-compass class="icon me-2" />
        Temukan
      </a>
    </li>
  @else
    <li class="nav-item">
      <a href="{{ route('kegiatan.index') }}" @class(['nav-link', 'active' => request()->routeIs('kegiatan.index')])>
        <x-lucide-calendar-arrow-up class="icon me-2" />
        Mendatang
      </a>
    </li>
  @endhaspermission
  <li class="nav-item">
    <a href="" class="nav-link">
      <x-lucide-calendar-clock class="icon me-2" />
      Histori
    </a>
  </li>
</ul>
