@props([
  'id',
  'name',
  'type' => 'text',
  'showError' => true,
])

@php
  $attr = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name),]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" {{ $attr }} autocomplete="off">
@if($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
