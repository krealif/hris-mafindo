@props([
    'id' => null,
    'name' => null,
    'showError' => true,
    'showIcon' => true,
    'maxDate' => null,
    'minDate' => null,
    'disabled' => false,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name)]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

@if ($showIcon)
  <div class="input-icon">
    <span class="input-icon-addon">
      <x-lucide-calendar class="icon" />
    </span>
    <input id="{{ $id }}" name="{{ $name }}" type="text" autocomplete="off" {{ $attributes }} @disabled($disabled)>
  </div>
@else
  <input id="{{ $id }}" name="{{ $name }}" type="text" autocomplete="off" {{ $attributes }} @disabled($disabled)>
@endif
@if ($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
@isset($script)
  {{ $script }}
@else
  <script>
    document.addEventListener("DOMContentLoaded", function() {
      const options = {
        locale: 'id',
        altInput: true,
        altFormat: "d/m/Y",
        dateFormat: "Y-m-d",
      };

      @if ($maxDate)
        options['maxDate'] = @js($maxDate);
      @endif
      @if ($minDate)
        options['minDate'] = @js($minDate);
      @endif

      if (window.flatpickr) {
        flatpickr(@js('#' . $id), options);
      }
    });
  </script>
@endisset

@once
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/vendor/flatpickr.min.css') }}">
  @endpush
  @push('scripts')
    <script src="{{ asset('static/vendor/flatpickr.min.js') }}" defer></script>
    <script src="{{ asset('static/vendor/flatpickr.id.js') }}" defer></script>
  @endpush
@endonce
