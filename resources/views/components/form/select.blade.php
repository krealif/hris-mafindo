@props([
    'id' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'showError' => true,
    'required' => false,
    'disabled' => false,
])

@php
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
  $selectedValue = old($name, $selected);
  $attributes = $attributes->class(['form-select', 'is-invalid' => $showError && $errors->has($name)]);
@endphp

<select id="{{ $id }}" name="{{ $name }}" autocomplete="off" {{ $attributes }} @required($required) @disabled($disabled)>
  @isset($attributes['placeholder'])
    <option value="" selected>{{ $attributes['placeholder'] ?? '' }}</option>
  @endisset
  @foreach ($options as $value => $label)
    <option value="{{ $value }}" @selected($selectedValue == $value)>{{ $label }}</option>
  @endforeach
</select>
@if ($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
