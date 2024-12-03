@props([
    'id' => null,
    'name' => null,
    'type' => 'text',
    'showError' => true,
    'required' => false,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name)]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<input id="{{ $id }}" name="{{ $name }}" type="{{ $type }}" autocomplete="off" {{ $attributes }} @required($required)>
@if ($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
