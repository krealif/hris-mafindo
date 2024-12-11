@props([
    'id' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'showError' => true,
    'required' => false,
])

@php
  $attributes = $attributes->class(['form-select', 'is-invalid' => $showError && $errors->has($name)]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
  $selectedValue = old($name, $selected);
@endphp

<select id="{{ $id }}" name="{{ $name }}" autocomplete="off" {{ $attributes }} @required($required)>
  {{ $slot }}
  @foreach ($options as $value => $label)
    <option value="{{ $value }}" @selected($selectedValue == $value)>{{ $label }}</option>
  @endforeach
</select>
<script>
  document.addEventListener("DOMContentLoaded", function() {
    window.flatpickr && (new TomSelect({{ Js::from('#' . $id) }}));
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
    <link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
  @endpush
  @push('scripts')
    <script src="{{ asset('static/vendor/tom-select.complete.min.js') }}"></script>
  @endpush
@endonce
