@props([
  'id',
  'name',
  'options' => [],
  'optVal'=> "id",
  'optLabel' => "name",
  'selected' => null,
  'showError' => true,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name),]);
  $id = $id ?? Str::kebab(Str::replace('_', ' ', $name));
  $selectedValue = old($name, $selected);

  if (is_object($options)) {
    $options = $options->toArray();
  }
@endphp

<select id="{{ $id }}" name="{{ $name }}" autocomplete="off" {{ $attributes }}>
  {{ $slot }}
  @foreach ($options as $option)
  <option value="{{ $option[$optVal] }}" @selected($selectedValue == $option[$optVal])>{{ $option[$optLabel] }}</option>
  @endforeach
</select>
<script>new TomSelect('#{{ $id }}');</script>
@if($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
