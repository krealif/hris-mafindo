@props([
    'id' => null,
    'name' => null,
    'options' => [],
    'selected' => null,
    'showError' => true,
    'required' => false,
])

@php
  $attributes = $attributes->class(['form-control', 'is-invalid' => $showError && $errors->has($name)]);
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
  new TomSelect('#{{ $id }}');
</script>
@if ($showError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
