@props([
  'id',
  'name',
  'showError' => true,
])

@php
  $attr = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name),]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<textarea id="{{ $id }}" name="{{ $name }}" {{ $attr }} autocomplete="off">{{ $slot }}</textarea>
@if($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
