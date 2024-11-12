@props([
  'selected' => null
])

<x-form.select-searchable {{ $attributes }} :withError=false selected="{{ request()->filter[$attributes->get('name')] ?? '' }}">
  <x-slot:placeholder>
    {{ $placeholder }}
  </x-slot>
</x-form.select-searchable>
