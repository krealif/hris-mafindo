@props([
    'id' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'showError' => true,
    'required' => false,
    'script' => null,
])

@php
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
  $selectedValue = explode(',', old($name, $selected ?? ''));
  $attributes = $attributes->class(['form-select', 'is-invalid' => $showError && $errors->has($name)]);
@endphp

<select id="{{ $id }}" name="{{ $name }}" autocomplete="off" {{ $attributes }} @required($required)>
  @isset($attributes['placeholder'])
    <option value="" selected>{{ '' && $attributes['placeholder'] }}</option>
  @endisset
  @foreach ($options as $value => $label)
    <option value="{{ $value }}" @selected(in_array($value, $selectedValue))>{{ $label }}</option>
  @endforeach
</select>
@if ($showError && !$attributes['multiple'])
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
      if (window.TomSelect) {
        new TomSelect(@js('#' . $id), {
          plugins: @json($attributes['multiple'] ? ['caret_position', 'checkbox_options'] : []),
          onItemAdd: function() {
            this.setTextboxValue('');
          },
        });
      }
    });
  </script>
@endisset
@once
  @push('styles')
    <link rel="stylesheet" href="{{ asset('static/vendor/tom-select.min.css') }}">
  @endpush
  @push('scripts')
    <script src="{{ asset('static/vendor/tom-select.complete.min.js') }}" defer></script>
  @endpush
@endonce
