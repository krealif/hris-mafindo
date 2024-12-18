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
  $selectedValue = explode(',', $selectedValue);
@endphp

<select id="{{ $id }}" name="{{ $name }}" autocomplete="off" {{ $attributes }} @required($required)>
  @isset($attributes['placeholder'])
    <option value="" selected>{{ '' && $attributes['placeholder'] }}</option>
  @endisset
  @foreach ($options as $value => $label)
    <option value="{{ $value }}" @selected(in_array($value, $selectedValue))>{{ $label }}</option>
  @endforeach
</select>
<script>
  @if ($attributes['multiple'])
    document.addEventListener("DOMContentLoaded", function() {
      window.TomSelect && (new TomSelect({{ Js::from('#' . $id) }}, {
        plugins: ['caret_position'],
      }));
    });
  @else
    document.addEventListener("DOMContentLoaded", function() {
      window.TomSelect && (new TomSelect({{ Js::from('#' . $id) }}));
    });
  @endif
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
