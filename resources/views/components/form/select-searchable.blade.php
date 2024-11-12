@props([
  'id',
  'name',
  'options' => [],
  'withError' => true,
  'optVal'=> "id",
  'optLabel' => "name",
  'selected' => null
])

@php
  $attr = $attributes->class(['form-control', 'is-invalid' => $withError && $errors->has($name),]);
  $nameKebab = Str::kebab(Str::replace('_', ' ', $name));
  $selectedValue = old($name, $selected);
@endphp

<select id="{{ $id ?? $nameKebab }}" name="{{ $name }}" autocomplete="off">
  {{ $placeholder }}
  @if(is_object($options))
    @foreach ($options as $option)
    <option value="{{ $option->{$optVal} }}" {{ $selectedValue == $option->{$optVal} ? 'selected' : '' }}>{{ $option->{$optLabel} }}</option>
    @endforeach
  @else
    @foreach ($options as $option)
    <option value="{{ $option[$optVal] }}" {{ $selectedValue == $option[$optVal] ? 'selected' : '' }}>{{ $option[$optLabel] }}</option>
    @endforeach
  @endif
</select>
<script>new TomSelect('#{{ $id ?? $nameKebab }}');</script>
@if($withError)
  @error($name)
    <div class="invalid-feedback" role="alert">
      <strong>{{ $message }}</strong>
    </div>
  @enderror
@endif
