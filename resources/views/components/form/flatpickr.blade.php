@props([
    'id' => null,
    'name' => null,
    'showError' => true,
    'required' => false,
    'maxDate' => null,
    'minDate' => null,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name)]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<div class="input-icon">
  <span class="input-icon-addon"><!-- Download SVG icon from http://tabler-icons.io/i/calendar -->
    <x-lucide-calendar class="icon" />
  </span>
  <input id="{{ $id }}" name="{{ $name }}" type="text" autocomplete="off" {{ $attributes }} @required($required)>
</div>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    const options = {
      locale: 'id',
      altInput: true,
      altFormat: "d/m/Y",
      dateFormat: "Y-m-d",
    };

    @if ($maxDate || $minDate)
      options['{{ $maxDate ? 'maxDate' : 'minDate' }}'] = '{{ $maxDate ?? $minDate }}';
    @endif

    if (window.flatpickr) {
      flatpickr({{ Js::from('#' . $id) }}, options);
    }
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
    <link rel="stylesheet" href="{{ asset('static/vendor/flatpickr.min.css') }}">
  @endpush
  @push('scripts')
    <script src="{{ asset('static/vendor/flatpickr.min.js') }}"></script>
    <script src="{{ asset('static/vendor/flatpickr.id.js') }}"></script>
  @endpush
@endonce
