<div {{ $attributes->merge(['class' => 'alert alert-important alert-dismissible']) }}>
  <div class="d-flex">
    {{ $slot }}
  </div>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="close"></button>
</div>
