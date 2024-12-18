@props([
    'type' => null,
])

@php
  $icon = match ($type) {
      'alert-success' => 'lucide-check',
      'alert-danger' => 'lucide-octagon-alert',
      'alert-info' => 'lucide-info',
      default => 'lucide-info',
  };
@endphp

<div {{ $attributes->class([$type, 'alert alert-important alert-dismissible fade show']) }} role="alert">
  <div class="d-flex">
    <div class="col-auto">
      @svg($icon, 'icon alert-icon')
    </div>
    <div class="col fw-bold">
      {{ $slot }}
    </div>
  </div>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></button>
</div>
