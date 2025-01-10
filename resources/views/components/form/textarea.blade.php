@props([
    'id' => null,
    'name' => null,
    'showError' => true,
    'required' => false,
    'disabled' => false,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name)]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
@endphp

<textarea id="{{ $id }}" name="{{ $name }}" autocomplete="off" {{ $attributes }} @required($required) @disabled($disabled)>{{ $slot }}</textarea>
@if ($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
