@props(['type' => 'alert-info'])

<div {{ $attributes->class([$type, 'alert alert-important alert-dismissible']) }}>
  <div class="d-flex">
    @switch($type)
      @case('alert-success')
        <x-lucide-check class="icon alert-icon" />
      @break

      @case('alert-danger')
        <x-lucide-octagon-alert class="icon alert-icon" />
      @break

      @default
        <x-lucide-info class="icon alert-icon" />
    @endswitch
    <div>
      {{ $slot }}
    </div>
  </div>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></button>
</div>
