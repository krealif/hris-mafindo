@props([
  'id',
  'name',
  'options' => [],
  'optVal'=> "id",
  'optLabel' => "name",
  'selected' => null,
  'withError' => true,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $withError && $errors->has($name),]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
  $selectedValue = old($name, $selected);

  if (is_object($options)) {
    $options = $options->toArray();
  }
@endphp

<select id="{{ $id }}" name="{{ $name }} autocomplete="off" {{ $attributes }}">
  {{ $slot }}
  @foreach ($options as $option)
  <option value="{{ $option[$optVal] }}" {{ $selectedValue == $option[$optVal] ? 'selected' : '' }}>{{ $option[$optLabel] }}</option>
  @endforeach
</select>
@if($withError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
