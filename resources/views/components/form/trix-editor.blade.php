@props([
    'id' => null,
    'name' => null,
    'showError' => true,
    'required' => false,
    'disabled' => false,
    'value' => null,
])

@php
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<input id="{{ $id }}" name="{{ $name }}" autocomplete="off" type="hidden" value="{{ $value }}">
<trix-editor id="trix-{{ $id }}" input="{{ $id }}" @class([
    'form-control',
    'is-invalid' => $showError && $errors->has($name),
]) @required($required) @disabled($disabled)></trix-editor>
<script>
  document.addEventListener("trix-initialize", function(e) {
    const actions = [
      '.trix-button-group--file-tools',
      '.trix-button--icon-code',
      '.trix-button--icon-quote'
    ];

    actions.forEach(e => {
      document.querySelector(e)?.remove();
    });

  });
  document.addEventListener("trix-attachment-add", function(event) {
    if (!event.attachment.file) {
      event.attachment.remove()
    }
  })
  document.addEventListener("trix-file-accept", function(event) {
    event.preventDefault();
  });
</script>
@if ($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif

@once
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/vendor/trix.min.css') }}">
  @endpush
  @push('scripts')
    <script src="{{ asset('static/vendor/trix.umd.min.js') }}" defer></script>
  @endpush
@endonce
